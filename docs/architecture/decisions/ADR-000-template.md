# ADR-000: Template para Architecture Decision Records

- **Status:** `Accepted`
- **Data:** 2026-04-29
- **Autores:** Bruno Oliveira

---

## Contexto

Decisões arquiteturais importantes são tomadas ao longo do desenvolvimento do projeto. Sem registro formal, o raciocínio por trás dessas decisões se perde com o tempo, tornando difícil entender por que o sistema foi construído de determinada forma.

## Decisão

Adotamos o formato ADR (Architecture Decision Record) para documentar decisões arquiteturais relevantes. Cada ADR é um documento imutável que registra uma decisão específica, seu contexto e suas consequências.

## Estrutura de um ADR

```
# ADR-NNN: Título da Decisão

- **Status:** `Proposed` | `Accepted` | `Deprecated` | `Superseded by ADR-NNN`
- **Data:** YYYY-MM-DD
- **Autores:** Nome(s)

---

## Contexto

> Descreva a situação que motivou a decisão. Qual problema estava sendo resolvido?
> Quais forças, restrições ou requisitos influenciaram a escolha?

## Decisão

> Descreva a decisão tomada de forma clara e direta.
> "Decidimos usar X porque Y."

## Alternativas Consideradas

> Liste as opções que foram avaliadas e descartadas, com justificativa resumida.

| Alternativa | Motivo da Rejeição |
|-------------|-------------------|
| Opção A     | ...               |
| Opção B     | ...               |

## Consequências

### Positivas
- ...

### Negativas / Trade-offs
- ...

## Referências

- Link ou documento relevante
```

---

## Status dos ADRs

| Status | Significado |
|--------|------------|
| `Proposed` | Em discussão, ainda não aceito |
| `Accepted` | Decisão tomada e em vigor |
| `Deprecated` | Substituído por nova abordagem, mas sem ADR substituto |
| `Superseded by ADR-NNN` | Substituído pelo ADR indicado |

---

## Regras

1. ADRs são **imutáveis**. Nunca edite o conteúdo de um ADR aceito.
2. Se uma decisão mudar, crie um novo ADR com status `Superseded by ADR-NNN` no ADR antigo.
3. Numere sequencialmente: `ADR-001`, `ADR-002`, etc.
4. Mantenha o índice em `docs/architecture/decisions/README.md` atualizado.
