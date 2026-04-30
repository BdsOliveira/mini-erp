# Índice de Especificações

Visão consolidada de todas as specs do projeto. Atualizar sempre que uma spec mudar de status.

---

## Status Possíveis

| Status | Descrição |
|--------|-----------|
| `Draft` | Em escrita. Ainda não revisada. |
| `In Review` | Aberta para revisão do time. |
| `Ready for Development` | Aprovada. Pode ser implementada. |
| `Implemented` | Código entregue e DoD verificado. |
| `Deprecated` | Feature removida ou substituída. |

---

## Specs Ativas

| ID | Título | Status | Módulo | Autor | Criado em |
|----|--------|--------|--------|-------|-----------|
| [SPEC-001](./001-multi-template/spec.md) | Sistema de Múltiplos Templates de UI | `Ready for Development` | `packages/framework/src/View/` | Bruno Oliveira | 2026-04-29 |
| [SPEC-002](./002-autenticacao/spec.md) | Autenticação do Sistema | `Draft` | `app/Modules/Auth/`, `Admin/`, `Account/` | Bruno Oliveira | 2026-04-29 |

---

## Specs Implementadas

_Nenhuma ainda._

---

## Specs Depreciadas

_Nenhuma ainda._

---

## Como Criar uma Nova Spec

1. Defina o próximo número sequencial disponível neste índice.
2. Crie a pasta e copie o template:
   ```bash
   mkdir docs/specs/NNN-nome-da-feature
   cp docs/specs/spec-template.md docs/specs/NNN-nome-da-feature/spec.md
   ```
3. Preencha o spec.md com todas as seções.
4. Adicione a entrada neste índice com status `Draft`.
5. Abra para revisão do time.
6. Após aprovação, atualize o status para `Ready for Development`.

Consulte `docs/specs/sdd-process.md` para o processo completo.
