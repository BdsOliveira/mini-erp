# [SPEC-001] Sistema de Múltiplos Templates de UI

- **Status:** `Ready for Development`
- **Módulo:** `packages/framework/src/View/`
- **Criado em:** 2026-04-29
- **Autor:** Bruno Oliveira

---

## 1. Contexto e Problema

Atualmente, o sistema de visualização possui caminhos de diretórios fixos em `resources/views/`. Isso impede que a aplicação suporte diferentes "temas" ou layouts (ex: `default`, `modern`) de forma dinâmica sem alterações no código-fonte.

Além disso, a gestão de ativos (CSS/JS) para diferentes temas carece de um mecanismo de fallback: se um tema novo for criado, ele é obrigado a replicar todos os ativos do tema padrão, mesmo que deseje apenas customizar uma pequena parte da interface.

## 2. Objetivo

Implementar um sistema de templates dinâmico que permita:
1. Trocar o tema ativo globalmente via banco de dados ou por sessão de usuário.
2. Garantir que, se uma view ou asset não existir no tema customizado, o sistema utilize automaticamente a versão do tema `default`.
3. Manter a performance da aplicação evitando consultas redundantes ao banco de dados durante o ciclo de renderização.
4. Utilizar o framework **Tailwind CSS** para a composição da interface de usuário, garantindo uma base visual moderna, responsiva e de fácil customização entre os diferentes templates.

---

## 3. Arquitetura Proposta

### 3.1 Template Registry (`ViewConfig`)
Para garantir que a resolução de templates seja rápida e centralizada, utilizaremos uma classe de registro estático. O template ativo é definido uma única vez no início da requisição (Bootstrap) e consumido por todos os componentes subsequentes.

### 3.2 Resolução de Caminhos (Fallback Nativo)
O carregador do Twig (`FilesystemLoader`) será configurado com uma pilha de diretórios ordenada por prioridade:
1. `resources/views/templates/{active}/`
2. `resources/views/templates/default/` (Fallback)

### 3.3 Helper de Assets com Verificação Física
O helper `asset()` verificará a existência real do arquivo no disco antes de gerar a URL. Isso permite "skins" parciais onde apenas arquivos específicos são sobrescritos.

---

## 4. Definições Técnicas

### 4.1 Registry Estático
```php
namespace Framework\View;

final class ViewConfig
{
    private static string $activeTemplate = 'default';

    public static function set(string $template): void {
        self::$activeTemplate = $template;
    }

    public static function get(): string {
        return self::$activeTemplate;
    }
}
```

### 4.2 Lógica do Trait `HasTemplate`
O trait deve ser agnóstico à origem da configuração, consumindo apenas o `ViewConfig`.

```php
// packages/framework/src/View/Traits/HasTemplate.php

public function __construct()
{
    $active = ViewConfig::get();
    $root = dirname(__DIR__, 5); 
    
    $paths = [
        $root . '/resources/views/templates/' . $active,
        $root . '/resources/views/templates/default'
    ];

    $this->twig = new Environment(new FilesystemLoader(array_unique($paths)));
    $this->registerFunctions($active);
}
```

### 4.3 Helper `asset`
```php
function asset(string $path, string $activeTemplate): string
{
    $activeUrl = "/assets/templates/{$activeTemplate}/" . ltrim($path, '/');
    $defaultUrl = "/assets/templates/default/" . ltrim($path, '/');
    
    $publicPath = dirname(__DIR__, 5) . '/public';
    
    return file_exists($publicPath . $activeUrl) ? $activeUrl : $defaultUrl;
}
```

---

## 5. Regras de Negócio

| ID    | Regra                                                                                                                    |
|-------|--------------------------------------------------------------------------------------------------------------------------|
| RN-01 | O template `default` é obrigatório e imutável. Serve como a base estável do sistema.                                     |
| RN-02 | A sessão do usuário (`active_template`) tem precedência sobre a configuração global do banco de dados.                    |
| RN-03 | Se o diretório do template configurado não existir, o sistema deve assumir `default` silenciosamente.                    |
| RN-04 | Assets de terceiros (bibliotecas) devem permanecer em `public/js/` ou `public/css/` comuns, fora da estrutura de templates. |

---

## 6. Estrutura de Arquivos Final

```
resources/views/templates/
├── default/          # Template base completo
└── modern/           # Template customizado (pode ser parcial)

public/assets/templates/
├── default/          # CSS/JS base
└── modern/           # Overrides específicos
```

---

## 7. Critérios de Aceite (DoD)

- [ ] A troca de tema no banco de dados reflete na aplicação sem alterações de código.
- [ ] Views inexistentes no tema ativo carregam do `default` sem erros.
- [ ] Assets inexistentes no tema ativo carregam do `default` via helper `{{ asset() }}`.
- [ ] Testes unitários validam a lógica de prioridade (Sessão > Banco > Default).
- [ ] O `ViewConfig` é populado no bootstrap da aplicação, evitando múltiplas queries.
