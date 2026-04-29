# [SPEC-001] Sistema de Múltiplos Templates de UI

- **Status:** `Ready for Development`
- **Módulo:** `packages/framework/src/View/`
- **Criado em:** 2026-04-29
- **Autor:** Bruno Oliveira

---

## 1. Contexto e Problema

Atualmente, o `HasTemplate` (trait em `packages/framework/src/View/Traits/HasTemplate.php`) inicializa o `FilesystemLoader` do Twig com um caminho fixo: `resources/views/`. Isso impede que a aplicação carregue diferentes layouts ou "temas" de interface sem alterar o código-fonte.

O objetivo é desacoplar a resolução do caminho das views da inicialização do Controller, introduzindo um componente `TemplateResolver` responsável por determinar dinamicamente qual diretório de template usar antes de renderizar qualquer view.

### Estado atual (`HasTemplate.php`)

```php
public function __construct()
{
    $root = dirname(getcwd());
    $loader = new FilesystemLoader($root . '/resources/views/');  // ← caminho fixo
    $this->twig = new Environment($loader);
    $this->registerFunctions();
}
```

### Problema concreto

- Não há como selecionar um tema diferente sem alterar o código-fonte.
- O construtor do `HasTemplate` é o único ponto de inicialização do Twig, o que acopla a resolução de tema à instanciação do Controller.
- Não existe a tabela `configurations` nem qualquer mecanismo de configuração persistida para preferências de UI.

---

## 2. Objetivo

Permitir que a aplicação carregue diferentes templates de UI (ex: `default`, `modern`) de forma dinâmica, com base em configuração persistida em banco de dados ou sessão, sem que os Controllers precisem conhecer ou condicionar qual template está ativo.

---

## 3. Casos de Uso

### UC-01: Renderização com template ativo

- **Ator:** Sistema (chamada interna via `BaseController::render()`)
- **Pré-condições:** Existe ao menos um template registrado e ativo (`default`). O `TemplateResolver` está integrado ao `HasTemplate`.
- **Fluxo Principal:**
  1. Controller chama `$this->render('produtos/index', $data)`.
  2. `HasTemplate` consulta o `TemplateResolver` para obter o nome do template ativo.
  3. `TemplateResolver` verifica a sessão; se não houver preferência, busca a configuração global no banco de dados.
  4. `TemplateResolver` retorna o slug do template ativo (ex: `default`).
  5. `HasTemplate` monta o caminho: `resources/views/templates/{slug}/`.
  6. Twig carrega e renderiza o arquivo `produtos/index` dentro desse diretório.
- **Fluxo Alternativo — arquivo não encontrado no template ativo:**
  - A1: O arquivo não existe no diretório `templates/{slug}/`.
  - A2: `TemplateResolver` instrui o Twig a fazer fallback para `templates/default/`.
  - A3: O arquivo é carregado do template `default`.
- **Pós-condições:** A view correta é renderizada sem nenhuma condicional no Controller.

### UC-02: Troca de template via configuração global (admin)

- **Ator:** Administrador
- **Pré-condições:** Existe mais de um template disponível em `resources/views/templates/`.
- **Fluxo Principal:**
  1. Admin acessa a tela de configurações e seleciona um template disponível.
  2. O sistema persiste a preferência global na tabela `configurations` com chave `active_template`.
  3. A partir da próxima requisição, todas as views são carregadas do novo template.
- **Pós-condições:** O template ativo é atualizado globalmente sem redeployment.

### UC-03: Troca de template via sessão (por usuário/grupo — futuro)

- **Ator:** Sistema / Sessão do usuário
- **Pré-condições:** A sessão contém a chave `active_template`.
- **Fluxo Principal:**
  1. No início da requisição, `TemplateResolver` verifica `Session::get('active_template')`.
  2. Se existir, usa esse valor como slug do template ativo (tem precedência sobre a config global).
  3. O Twig é inicializado com o diretório correspondente.
- **Pós-condições:** Usuários diferentes podem visualizar templates distintos sem afetar outros.

---

## 4. Regras de Negócio

| ID    | Regra                                                                                                                    |
|-------|--------------------------------------------------------------------------------------------------------------------------|
| RN-01 | O template `default` é obrigatório e imutável como fallback. Nunca pode ser removido ou renomeado.                       |
| RN-02 | Se o template ativo não for encontrado em disco, o sistema deve logar o erro e usar o `default` silenciosamente.         |
| RN-03 | Controllers nunca devem receber o nome ou slug do template como parâmetro ou variável.                                   |
| RN-04 | A sessão tem precedência sobre a configuração global ao resolver o template ativo.                                        |
| RN-05 | O `TemplateResolver` deve ser o único ponto de leitura da configuração de template em toda a aplicação.                  |
| RN-06 | Assets (CSS/JS) de cada template devem ficar em `public/assets/templates/{slug}/` e ser referenciados via helper de URL. |

---

## 5. Contratos de Interface

### 5.1 `TemplateResolverInterface`

```php
// packages/framework/src/View/TemplateResolverInterface.php

namespace Framework\View;

interface TemplateResolverInterface
{
    /**
     * Retorna o slug do template ativo (ex: 'default', 'modern').
     * Nunca lança exceção — retorna 'default' como fallback garantido.
     */
    public function resolve(): string;
}
```

### 5.2 `DatabaseTemplateResolver`

```php
// packages/framework/src/View/DatabaseTemplateResolver.php

namespace Framework\View;

use Framework\Database\Database;
use Framework\Utils\Session;

final class DatabaseTemplateResolver implements TemplateResolverInterface
{
    public function __construct(private readonly Database $db) {}

    public function resolve(): string
    {
        // 1. Sessão tem prioridade (UC-03)
        $fromSession = Session::get('active_template');
        if (is_string($fromSession) && $fromSession !== '') {
            return $fromSession;
        }

        // 2. Configuração global persistida no banco (UC-02)
        $row = $this->db->query(
            "SELECT value FROM configurations WHERE `key` = 'active_template' LIMIT 1"
        );
        if (!empty($row[0]['value'])) {
            return $row[0]['value'];
        }

        // 3. Fallback garantido (RN-01)
        return 'default';
    }
}
```

### 5.3 Modificação em `HasTemplate`

O construtor passa a receber o `TemplateResolverInterface` via injeção e configura o `FilesystemLoader` com dois caminhos (template ativo + `default` como fallback nativo do Twig):

```php
// packages/framework/src/View/Traits/HasTemplate.php

public function __construct()
{
    $root   = dirname(getcwd());
    $db     = \Framework\Database\Connection::getInstance();
    $resolver = new \Framework\View\DatabaseTemplateResolver($db);

    $activeTemplate = $resolver->resolve();
    $templatePath   = $root . '/resources/views/templates/' . $activeTemplate . '/';
    $fallbackPath   = $root . '/resources/views/templates/default/';

    // RN-02: verificação de existência antes de inicializar o Twig
    if (!is_dir($templatePath)) {
        error_log("[TemplateResolver] Template '{$activeTemplate}' não encontrado em disco. Usando 'default'.");
        $templatePath = $fallbackPath;
    }

    $loader = new FilesystemLoader(
        array_unique([$templatePath, $fallbackPath])  // fallback nativo do Twig
    );

    $this->twig = new Environment($loader);
    $this->registerFunctions($resolver);
}
```

> **Nota:** `array_unique` garante que, quando o template ativo já é `default`, não haja duplicata de caminho.

### 5.4 Helper de Assets

```php
// packages/framework/src/View/functions/asset.php

namespace Framework\View\functions;

use Framework\View\TemplateResolverInterface;

function asset(string $path, TemplateResolverInterface $resolver): string
{
    $slug = $resolver->resolve();
    return '/assets/templates/' . $slug . '/' . ltrim($path, '/');
}
```

Registro da função Twig no `HasTemplate::registerFunctions()`:

```php
private function registerFunctions(TemplateResolverInterface $resolver): void
{
    // ...funções existentes (dd, getCartItemsQtd)...

    $this->twig->addFunction(
        new TwigFunction('asset', fn(string $path) => asset($path, $resolver))
    );
}
```

Uso nas views Twig:

```twig
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
{# Resolve para: /assets/templates/modern/css/style.css #}
```

### 5.5 Rotas HTTP (Admin)

Adicionar em `app/Modules/Core/routes.php`:

```php
'GET'  => [
    '/admin/configuracoes' => [ListConfiguracoesController::class, 'index'],
    // ...rotas existentes
],
'POST' => [
    '/admin/configuracoes/tema' => [UpdateActiveTemplateController::class, 'handle'],
    // ...rotas existentes
],
```

| Método | Path                        | Controller                              | Responsabilidade                                       |
|--------|-----------------------------|-----------------------------------------|--------------------------------------------------------|
| `GET`  | `/admin/configuracoes`      | `ListConfiguracoesController`           | Renderiza tela de configurações com templates disponíveis |
| `POST` | `/admin/configuracoes/tema` | `UpdateActiveTemplateController`        | Persiste o slug do template na tabela `configurations` |

### 5.6 Schema de Banco de Dados

```sql
-- database/migrations/create_configurations_table.sql

CREATE TABLE configurations (
    id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key`  VARCHAR(100) NOT NULL UNIQUE,
    value  VARCHAR(255) NOT NULL
);

-- Seed inicial obrigatório (RN-01)
INSERT INTO configurations (`key`, value) VALUES ('active_template', 'default');
```

> **Nota:** O projeto utiliza arquivos `.sql` avulsos em `database/` (sem migration runner). O novo arquivo deve ser aplicado manualmente e referenciado em `database/schema.sql`.

---

## 6. Estrutura de Arquivos

```
packages/framework/src/View/
├── Traits/
│   └── HasTemplate.php                     ← Modificar: usar TemplateResolver + dois caminhos
├── TemplateResolverInterface.php           ← Criar: contrato
├── DatabaseTemplateResolver.php            ← Criar: implementação principal
└── functions/
    ├── dd.php                              ← Existente (não alterar)
    └── asset.php                           ← Criar: helper de assets

resources/views/
└── templates/                             ← Criar diretório
    ├── default/                            ← Mover views atuais para cá
    │   ├── layouts/
    │   │   ├── app.php
    │   │   └── loja.php
    │   ├── produtos/
    │   │   ├── index.php
    │   │   ├── form.php
    │   │   ├── edit-variant.php
    │   │   └── variants.php
    │   ├── loja/
    │   │   ├── index.php
    │   │   └── carrinho.php
    │   ├── checkout/
    │   │   └── index.php
    │   ├── pedidos/
    │   │   └── index.php
    │   ├── errors/
    │   │   └── not-found.php
    │   └── index.php
    └── modern/                             ← Criar: template alternativo (PoC)
        └── layouts/
            └── app.php

public/assets/
└── templates/
    ├── default/
    │   ├── css/
    │   └── js/
    └── modern/
        └── css/

database/
└── migrations/
    └── create_configurations_table.sql    ← Criar

app/Modules/Core/
├── Controllers/
│   ├── ErroController.php                 ← Existente (não alterar)
│   ├── HomeController.php                 ← Existente (não alterar)
│   ├── ListConfiguracoesController.php    ← Criar
│   └── UpdateActiveTemplateController.php ← Criar
└── routes.php                             ← Modificar: adicionar novas rotas
```

---

## 7. Sequência de Implementação

A ordem abaixo minimiza retrabalho e garante que cada etapa seja testável de forma independente.

```
Etapa 1 — Banco de dados
  └── Criar database/migrations/create_configurations_table.sql
  └── Aplicar migration e seed (active_template = 'default')

Etapa 2 — Framework (packages/framework)
  └── Criar TemplateResolverInterface.php
  └── Criar DatabaseTemplateResolver.php
  └── Criar functions/asset.php
  └── Modificar HasTemplate.php

Etapa 3 — Views
  └── Criar resources/views/templates/
  └── Mover todas as views atuais para templates/default/
  └── Criar templates/modern/layouts/app.php (PoC)

Etapa 4 — Assets
  └── Criar public/assets/templates/default/ e modern/
  └── Mover/duplicar assets existentes para templates/default/

Etapa 5 — Módulo Core (app)
  └── Criar ListConfiguracoesController
  └── Criar UpdateActiveTemplateController
  └── Atualizar app/Modules/Core/routes.php

Etapa 6 — Testes
  └── Criar packages/framework/tests/Unit/DatabaseTemplateResolverTest.php
```

---

## 8. Critérios de Aceite (DoD)

- [ ] `TemplateResolver` resolve o template ativo via sessão ou banco de dados, sem uso de variáveis de ambiente.
- [ ] Cada template tem seus arquivos isolados em `resources/views/templates/{slug}/`.
- [ ] Controllers chamam `$this->render('view-name', $data)` sem qualquer referência ao template ativo.
- [ ] Fallback para `default` funciona automaticamente quando um arquivo não existe no template ativo.
- [ ] Nenhum `if ($template === 'x')` existe em Controllers, Services ou Models.
- [ ] Assets são referenciados via `{{ asset('...') }}` nas views Twig, apontando para o diretório do template ativo.
- [ ] A troca de template via painel admin persiste na tabela `configurations` e é refletida na próxima requisição.
- [ ] Testes unitários cobrem `DatabaseTemplateResolver` (resolução por sessão, por BD, fallback).
- [ ] Todos os arquivos PHP seguem `declare(strict_types=1)`.
- [ ] Views e assets do template `modern` (PoC) renderizam corretamente ao ativar o tema.

---

## 9. Fora de Escopo

- Cache de configuração de template (ex: Redis/Memcached).
- Interface de upload ou instalação de novos templates via painel.
- Temas por usuário autenticado (a sessão pode ser usada manualmente, mas não há UI para isso neste ciclo).
- Compilação/build de assets (Vite, Webpack, etc.).
- Suporte a sub-templates ou herança entre templates.

---

## 10. Notas e Decisões Técnicas

**Por que dois caminhos no `FilesystemLoader` em vez de lógica de fallback manual?**
O Twig já suporta múltiplos diretórios de busca nativamente, resolvendo na ordem fornecida. Isso elimina a necessidade de qualquer lógica condicional no PHP e aproveita um mecanismo já testado pela biblioteca.

**Por que `DatabaseTemplateResolver` e não apenas sessão?**
A sessão é volátil. Uma configuração global persistida em banco garante que todos os usuários e todas as requisições (incluindo requests de assets) recebam o mesmo template padrão sem depender de estado de sessão.

**Por que não usar injeção de dependência via construtor em `HasTemplate`?**
O `HasTemplate` é uma trait usada por `BaseController`, que não possui container de DI. A instanciação direta do `DatabaseTemplateResolver` dentro do construtor da trait é a abordagem pragmática compatível com a arquitetura atual. Refatorar para DI completo está fora do escopo desta spec.

**Onde vive o `TemplateResolver`?**
No `packages/framework`, pois é uma responsabilidade da camada de renderização do framework, não de nenhum módulo de negócio. O módulo `Core` apenas usa rotas para expor a configuração via HTTP.

**Por que `array_unique` ao montar os caminhos do `FilesystemLoader`?**
Quando o template ativo já é `default`, sem `array_unique` o mesmo diretório seria adicionado duas vezes ao loader, gerando redundância silenciosa. A deduplicação garante comportamento correto em todos os casos.

**Views com extensão `.php` vs `.twig`**
O projeto já usa `.php` como extensão das views carregadas pelo Twig. Essa convenção deve ser mantida em todos os templates novos para consistência com o código existente.
