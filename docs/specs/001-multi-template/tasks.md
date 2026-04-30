# [SPEC-001] Tasks de Implementação (Refinada)

- **Spec:** [spec.md](./spec.md)
- **Status:** `Pending`
- **Criado em:** 2026-04-29

---

## Etapa 1 — Infraestrutura e Dados

- [ ] Criar `database/migrations/create_configurations_table.sql` (Tabela `configurations`, seed `active_template = 'default'`).
- [ ] Aplicar a migration e atualizar `database/schema.sql`.
- [ ] Criar `packages/framework/src/View/ViewConfig.php` (Registry estático para o template ativo).

---

## Etapa 2 — Framework e Lógica de Resolução

- [ ] Criar `TemplateResolverInterface.php`.
- [ ] Criar `DatabaseTemplateResolver.php` (Implementa a busca em Session -> DB -> Fallback).
- [ ] Criar `functions/asset.php` com lógica de **Fallback de Arquivo** (verifica `file_exists`).
- [ ] Modificar `HasTemplate.php`:
  - Consumir template de `ViewConfig::get()`.
  - Usar caminhos absolutos baseados em `__DIR__`.
  - Registrar a função Twig `asset` passando o template ativo.

---

## Etapa 3 — Integração e Bootstrap

- [ ] Identificar o ponto de entrada comum (ex: `BaseController` ou `Application`).
- [ ] Implementar a chamada ao `DatabaseTemplateResolver` e o preenchimento do `ViewConfig::set()` no início do ciclo de vida da requisição.
- [ ] Garantir que o `Connection` do banco esteja disponível para o resolver.

---

## Etapa 4 — Migração de Frontend e Tailwind CSS

- [ ] Integrar **Tailwind CSS** (via CDN ou asset em `public/css/`) para uso global.
- [ ] Criar `resources/views/templates/default/`.
- [ ] Mover views atuais para `templates/default/`.
- [ ] Criar `public/assets/templates/default/`.
- [ ] Mover assets atuais para `templates/default/`.
- [ ] Criar template `modern` (PoC):
  - `resources/views/templates/modern/layouts/app.php` (Utilizando classes do **Tailwind CSS**).
  - `public/assets/templates/modern/css/style.css` (Apenas override parcial se necessário).

---

## Etapa 5 — Painel Administrativo

- [ ] Criar `ListConfiguracoesController` (Lista temas disponíveis via `scandir` em `views/templates`).
- [ ] Criar `UpdateActiveTemplateController` (Atualiza a tabela `configurations`).
- [ ] Criar view de configurações no template `default` (Utilizando **Tailwind CSS** para estilização).

---

## Etapa 6 — Testes e Validação

- [ ] Teste Unitário: `DatabaseTemplateResolver` (Prioridades e Fallback).
- [ ] Teste Unitário: `asset()` helper (Fallback de arquivo físico).
- [ ] Teste de Integração: Trocar tema no admin e verificar mudança visual imediata.
- [ ] Validar que `default` é usado se um arquivo de view faltar no `modern`.
- [ ] Verificar se as classes do **Tailwind CSS** estão sendo aplicadas corretamente no template `modern`.
