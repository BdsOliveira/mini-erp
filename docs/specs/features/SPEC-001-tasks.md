# [SPEC-001] Tasks de Implementação — Sistema de Múltiplos Templates de UI

- **Spec:** [SPEC-001-multi-template.md](./SPEC-001-multi-template.md)
- **Status:** `Pending`
- **Criado em:** 2026-04-29

---

## Etapa 1 — Banco de Dados

- [ ] Criar `database/migrations/create_configurations_table.sql` com a tabela `configurations` e seed inicial (`active_template = 'default'`)
- [ ] Aplicar a migration no banco e confirmar que a linha seed foi inserida
- [ ] Referenciar o novo arquivo em `database/schema.sql`

---

## Etapa 2 — Framework (`packages/framework/src/View/`)

- [ ] Criar `TemplateResolverInterface.php` com o método `resolve(): string`
- [ ] Criar `DatabaseTemplateResolver.php` implementando `TemplateResolverInterface` (sessão → banco → fallback `default`)
- [ ] Criar `functions/asset.php` com a função `asset(string $path, TemplateResolverInterface $resolver): string`
- [ ] Modificar `HasTemplate.php`:
  - Instanciar `DatabaseTemplateResolver` no construtor
  - Montar `$templatePath` e `$fallbackPath` com base no slug resolvido
  - Verificar existência do diretório do template ativo (RN-02: logar e usar `default` se ausente)
  - Passar `array_unique([$templatePath, $fallbackPath])` ao `FilesystemLoader`
  - Atualizar assinatura de `registerFunctions()` para receber e registrar o resolver
  - Registrar a `TwigFunction` `asset` via `registerFunctions()`

---

## Etapa 3 — Views

- [ ] Criar o diretório `resources/views/templates/`
- [ ] Mover todas as views existentes de `resources/views/` para `resources/views/templates/default/`, mantendo a estrutura de subpastas (`layouts/`, `produtos/`, `loja/`, `checkout/`, `pedidos/`, `errors/`)
- [ ] Criar `resources/views/templates/modern/layouts/app.php` como PoC do template alternativo

---

## Etapa 4 — Assets

- [ ] Criar estrutura `public/assets/templates/default/css/` e `public/assets/templates/default/js/`
- [ ] Criar estrutura `public/assets/templates/modern/css/`
- [ ] Mover ou duplicar os assets existentes de `public/assets/` para `public/assets/templates/default/`
- [ ] Atualizar referências de assets nas views do template `default` para usar `{{ asset('...') }}`

---

## Etapa 5 — Módulo Core (`app/Modules/Core/`)

- [ ] Criar `Controllers/ListConfiguracoesController.php`:
  - Método `index()` que lista templates disponíveis em disco (`resources/views/templates/`) e renderiza a view de configurações
- [ ] Criar `Controllers/UpdateActiveTemplateController.php`:
  - Método `handle()` que valida o slug recebido via POST, persiste em `configurations` e redireciona
- [ ] Criar view `resources/views/templates/default/admin/configuracoes/index.php` para a tela de configurações
- [ ] Atualizar `routes.php` adicionando:
  - `GET /admin/configuracoes` → `ListConfiguracoesController::index`
  - `POST /admin/configuracoes/tema` → `UpdateActiveTemplateController::handle`

---

## Etapa 6 — Testes

- [ ] Criar `packages/framework/tests/Unit/DatabaseTemplateResolverTest.php` cobrindo:
  - Resolução via sessão (sessão tem precedência)
  - Resolução via banco de dados quando sessão está vazia
  - Fallback para `default` quando nem sessão nem banco retornam valor
- [ ] Executar a suite de testes e garantir que todos passam

---

## Critérios de Aceite (DoD)

- [ ] `TemplateResolver` resolve o template ativo via sessão ou banco, sem variáveis de ambiente
- [ ] Cada template tem seus arquivos isolados em `resources/views/templates/{slug}/`
- [ ] Controllers chamam `$this->render('view-name', $data)` sem qualquer referência ao template ativo
- [ ] Fallback para `default` funciona automaticamente quando um arquivo não existe no template ativo
- [ ] Nenhum `if ($template === 'x')` existe em Controllers, Services ou Models
- [ ] Assets são referenciados via `{{ asset('...') }}` nas views Twig
- [ ] A troca de template via painel admin persiste na tabela `configurations` e é refletida na próxima requisição
- [ ] Testes unitários cobrem `DatabaseTemplateResolver` (sessão, BD, fallback)
- [ ] Todos os arquivos PHP seguem `declare(strict_types=1)`
- [ ] Views e assets do template `modern` (PoC) renderizam corretamente ao ativar o tema
