# ADR-001: Estrutura Modular por Contexto de Negócio

- **Status:** `Accepted`
- **Data:** 2026-04-29
- **Autores:** Bruno Oliveira

---

## Contexto

O projeto precisa suportar múltiplos domínios de negócio (catálogo, carrinho, checkout, agendamento, etc.) de forma organizada e extensível. A alternativa mais comum em projetos PHP seria uma estrutura por tipo de arquivo (todos os controllers juntos, todos os models juntos, etc.), mas isso gera acoplamento crescente conforme o projeto escala.

## Decisão

Adotamos uma estrutura modular onde cada contexto de negócio é um módulo isolado em `app/Modules/{NomeDoModulo}/`. Cada módulo contém todas as suas camadas internamente:

```
app/Modules/NomeDoModulo/
├── Controllers/
├── Services/
├── Repositories/
├── Models/
├── DTOs/
└── routes.php
```

Módulos transversais (utilitários sem contexto de negócio próprio) residem em:
- `app/Modules/Core/` — base do framework (BaseController, tratamento de erros)
- `app/Modules/Support/` — serviços compartilhados (frete, cupons)

## Alternativas Consideradas

| Alternativa | Motivo da Rejeição |
|-------------|-------------------|
| Estrutura por tipo (`/Controllers`, `/Models` na raiz) | Dificulta isolamento; acopla contextos diferentes no mesmo diretório |
| Domain-Driven Design completo (Aggregates, Value Objects, Events) | Complexidade excessiva para o escopo atual do projeto |
| Pacotes Composer separados por módulo | Overhead de versionamento desnecessário nesta fase |

## Consequências

### Positivas
- Isolamento claro: alterações em um módulo raramente afetam outros.
- Onboarding facilitado: desenvolvedores encontram tudo do contexto no mesmo lugar.
- Escalabilidade: novos módulos são adicionados sem reorganização do projeto.
- Testabilidade: cada módulo pode ser testado de forma independente.

### Negativas / Trade-offs
- Comunicação entre módulos exige disciplina: deve ocorrer via Services ou Repositories bem definidos, nunca por acesso direto a internals de outro módulo.
- Alguma duplicação aceitável: módulos similares podem ter código parecido (ex: dois módulos com repositórios de busca por ID). Isso é preferível ao acoplamento.

## Referências

- `docs/architecture/overview.md`
- `docs/standards/coding-conventions.md` — Regra 0 (Modularização e Isolamento)
