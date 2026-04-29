# [SPEC-XXX] Título da Feature

- **Status:** `Draft` | `Review` | `Approved` | `Implemented` | `Deprecated`
- **Módulo:** `app/Modules/[Modulo]/`
- **Criado em:** YYYY-MM-DD
- **Autor:** Nome

---

## 1. Contexto e Problema

> Descreva o problema de negócio que esta feature resolve. Seja objetivo.

## 2. Objetivo

> Uma frase clara descrevendo o que será entregue.

**Exemplo:** Permitir que o administrador aplique cupons de desconto manualmente em um pedido existente.

## 3. Casos de Uso

### UC-01: [Nome do caso]

- **Ator:** Admin / Cliente / Sistema
- **Pré-condições:** O que deve ser verdadeiro antes da ação.
- **Fluxo Principal:**
  1. Passo 1
  2. Passo 2
  3. Passo N
- **Fluxo Alternativo (se houver):**
  - A1: Descrição da variação
- **Pós-condições:** O que é verdadeiro após a ação ser concluída com sucesso.

### UC-02: [Nome do caso]

_(Repita o bloco acima para cada caso de uso adicional)_

## 4. Regras de Negócio

| ID    | Regra                                                  |
|-------|--------------------------------------------------------|
| RN-01 | Descreva a regra claramente, sem ambiguidade.          |
| RN-02 | ...                                                    |

## 5. Contratos de Interface

### 5.1 Rotas HTTP

| Método | Path                        | Controller (Single Action)           |
|--------|-----------------------------|--------------------------------------|
| `GET`  | `/exemplo/rota`             | `ExampleController`                  |
| `POST` | `/exemplo/acao`             | `CreateExampleController`            |

### 5.2 DTOs

**Input DTO (`CreateExampleDTO`):**

```php
readonly class CreateExampleDTO {
    public string $field;
    public int $otherId;
}
```

**Output / Response:**

> Descreva o formato da resposta (view renderizada, JSON, redirect, etc.)

## 6. Estrutura de Arquivos

```
app/Modules/[Modulo]/
├── Controllers/
│   └── CreateExampleController.php
├── Services/
│   └── CreateExampleService.php
├── Repositories/
│   └── CreateExampleRepository.php
├── DTOs/
│   └── CreateExampleDTO.php
└── Models/
    └── Example.php
```

## 7. Critérios de Aceite (DoD)

- [ ] Todos os casos de uso descritos funcionam conforme o esperado.
- [ ] Todas as regras de negócio estão implementadas e validadas.
- [ ] Testes unitários cobrem Services e Repositories.
- [ ] Nenhuma query SQL em Controllers ou Services.
- [ ] Código segue strict_types e Single Action.
- [ ] Rotas registradas em `app/Modules/[Modulo]/routes.php`.

## 8. Fora de Escopo

> Liste explicitamente o que **não** será feito nesta spec para evitar scope creep.

- Item fora de escopo 1
- Item fora de escopo 2

## 9. Notas e Decisões Técnicas

> Registre aqui decisões de design relevantes, trade-offs e justificativas.
