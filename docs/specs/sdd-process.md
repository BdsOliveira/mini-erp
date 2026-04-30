# Processo SDD (Specification-Driven Development)

Este documento define o processo de desenvolvimento orientado por especificações adotado neste projeto. O princípio central é: **nenhuma linha de código de feature é escrita antes de uma spec aprovada existir.**

---

## Por que SDD?

Em projetos modulares como este, a falta de especificação prévia gera:
- Retrabalho por mal-entendido de requisitos
- Acoplamento não planejado entre módulos
- Dificuldade de testar (o que testar se não há critérios definidos?)
- Decisões arquiteturais tomadas no meio da implementação

O SDD resolve isso forçando a clareza **antes** do código.

---

## O Ciclo SDD

```
[Ideia / Problema]
      │
      ▼
[1. Draft]                ← Autor escreve usando spec-template.md
      │
      ▼
[2. In Review]            ← Time revisa: casos de uso, regras, contratos
      │
      ▼
[3. Ready for Development] ← Spec congelada, pronta para implementação
      │
      ▼
[4. Implemented]          ← Código entregue, DoD checado, testes passando
      │
      ▼
[5. Deprecated]           ← (se a feature for removida ou substituída)
```

---

## Papéis

| Papel         | Responsabilidade                                                     |
|---------------|----------------------------------------------------------------------|
| **Autor**     | Escreve a spec, propõe casos de uso e regras de negócio.             |
| **Revisor**   | Questiona ambiguidades, valida contratos e critérios de aceite.      |
| **Implementador** | Segue a spec. Qualquer desvio deve atualizar a spec primeiro.   |

---

## Regras do Processo

1. **Spec primeiro.** Nenhum `Controller`, `Service` ou `Repository` é criado sem uma spec em status `Ready for Development`.

2. **Spec como contrato.** Se durante a implementação um requisito mudar, a spec deve ser atualizada e re-aprovada antes de continuar.

3. **Rastreabilidade.** O número da pasta da spec serve como identificador. Use o ID da spec em commits:
   ```
   feat(SPEC-001): implement multi-template system
   ```

4. **DoD é obrigatório.** A spec só vai para `Implemented` quando **todos** os itens da checklist de Critérios de Aceite estiverem marcados.

5. **Fora de escopo é sagrado.** Nada listado na seção "Fora de Escopo" pode ser implementado naquele ciclo sem uma nova spec.

---

## Estrutura de Arquivos

```
docs/specs/
├── spec-template.md              ← Template base para novas specs
├── sdd-process.md                ← Este arquivo
├── 001-multi-template/
│   └── spec.md                   ← Spec da feature
├── 002-[slug]/
│   └── spec.md
└── ...
```

### Nomenclatura de pastas

```
{número sequencial}-{slug-da-feature}/
```

**Exemplos:**
- `001-multi-template/`
- `002-order-refund/`
- `003-product-variants/`

### Cabeçalho obrigatório em toda spec

```markdown
# [SPEC-NNN] Título da Feature

- **Status:** `Draft`
- **Módulo:** `caminho/do/modulo/`
- **Criado em:** YYYY-MM-DD
- **Autor:** Nome do Autor
```

---

## Como criar uma nova Spec

1. Crie a pasta e copie o template:
   ```bash
   mkdir docs/specs/NNN-nome-da-feature
   cp docs/specs/spec-template.md docs/specs/NNN-nome-da-feature/spec.md
   ```

2. Preencha **todas** as seções. Seções em branco indicam spec incompleta.

3. Defina o status como `Draft` e abra para revisão.

4. Após aprovação do time, mude para `Ready for Development` e inicie a implementação.

---

## Checklist de Revisão

Use esta lista ao revisar uma spec antes de aprovar:

- [ ] O problema/contexto está claro e justifica a feature?
- [ ] Os casos de uso cobrem todos os fluxos relevantes (principal + alternativos)?
- [ ] As regras de negócio são verificáveis (não ambíguas)?
- [ ] Os contratos de interface (rotas, DTOs) estão alinhados com a arquitetura do projeto?
- [ ] A estrutura de arquivos segue o padrão modular (`app/Modules/[Modulo]/[Camada]`)?
- [ ] Os critérios de aceite são testáveis?
- [ ] O escopo está delimitado (seção "Fora de Escopo" preenchida)?
