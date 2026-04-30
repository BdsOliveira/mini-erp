# Diagrama Entidade-Relacionamento

Diagrama ER do banco de dados em formato Mermaid. Inclui tabelas atuais e pendentes (marcadas).

> Renderizável no GitHub, VSCode (extensão Mermaid) e em `https://mermaid.live`.

---

## Diagrama Atual

```mermaid
erDiagram
    users {
        int id PK
        varchar name
        varchar email UK
        varchar password
        timestamp created_at
        timestamp updated_at
    }

    produtos {
        int id PK
        varchar nome
        decimal preco
        text descricao
        longtext imagem
        tinyint status
        timestamp created_at
        timestamp updated_at
    }

    variacoes {
        int id PK
        int produto_id FK
        varchar tipo
        varchar valor
        varchar sku
        timestamp created_at
        timestamp updated_at
    }

    estoque {
        int id PK
        int produto_id FK
        int variacao_id FK "UNIQUE"
        int quantidade
        timestamp created_at
        timestamp updated_at
    }

    cupons {
        int id PK
        varchar codigo UK
        decimal desconto
        decimal valor_minimo
        int quantidade
        timestamp validade
        boolean ativo
        timestamp created_at
        timestamp updated_at
    }

    pedidos {
        int id PK
        int user_id
        decimal total
        varchar status
        timestamp created_at
        timestamp updated_at
    }

    produtos ||--o{ variacoes : "tem"
    variacoes ||--|| estoque : "controla"
    produtos ||--o{ estoque : "referencia"
```

---

## Diagrama com Tabelas Pendentes

Inclui tabelas que serão criadas pelas specs em andamento.

```mermaid
erDiagram
    users {
        int id PK
        varchar name
        varchar email UK
        varchar password
        enum role "admin|user (SPEC-002)"
        timestamp created_at
        timestamp updated_at
    }

    produtos {
        int id PK
        varchar nome
        decimal preco
        text descricao
        longtext imagem
        tinyint status
        timestamp created_at
        timestamp updated_at
    }

    variacoes {
        int id PK
        int produto_id FK
        varchar tipo
        varchar valor
        varchar sku
        timestamp created_at
        timestamp updated_at
    }

    estoque {
        int id PK
        int produto_id FK
        int variacao_id FK "UNIQUE"
        int quantidade
        timestamp created_at
        timestamp updated_at
    }

    cupons {
        int id PK
        varchar codigo UK
        decimal desconto
        decimal valor_minimo
        int quantidade
        timestamp validade
        boolean ativo
        timestamp created_at
        timestamp updated_at
    }

    pedidos {
        int id PK
        int user_id
        decimal total
        varchar status
        timestamp created_at
        timestamp updated_at
    }

    configurations {
        int id PK
        varchar key UK
        text value
        timestamp created_at
        timestamp updated_at
    }

    login_attempts {
        int id PK
        varchar email
        varchar ip
        timestamp attempted_at
    }

    password_resets {
        int id PK
        int user_id FK
        varchar token UK
        timestamp expires_at
        timestamp used_at
        timestamp created_at
    }

    produtos ||--o{ variacoes : "tem"
    variacoes ||--|| estoque : "controla"
    produtos ||--o{ estoque : "referencia"
    users ||--o{ password_resets : "solicita"
```

---

## Legenda

| Símbolo | Significado |
|---------|------------|
| `PK` | Primary Key |
| `FK` | Foreign Key |
| `UK` | Unique Key |
| `||--o{` | Um para muitos |
| `||--||` | Um para um |

---

## Notas

- A tabela `pedidos.user_id` não possui FK formal no schema atual (usuários eram gerados via `random_int`). Será formalizada após SPEC-002.
- Tabelas marcadas como _(SPEC-001)_ e _(SPEC-002)_ serão criadas via migrations quando as specs forem implementadas.
