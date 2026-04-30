# Esquema do Banco de Dados

Documentação das tabelas do banco de dados do projeto. O arquivo SQL de referência é `database/schema.sql`.

> Toda alteração de schema deve ser acompanhada de um arquivo de migration em `database/migrations/` e atualização deste documento.

---

## Tabelas

### `users`

Armazena todos os usuários do sistema (admins e usuários finais).

| Coluna | Tipo | Nulo | Padrão | Descrição |
|--------|------|------|--------|-----------|
| `id` | `INT` AUTO_INCREMENT | Não | — | Chave primária |
| `name` | `VARCHAR(255)` | Não | — | Nome completo |
| `email` | `VARCHAR(255)` | Não | — | Email único do usuário |
| `password` | `VARCHAR(255)` | Não | — | Hash bcrypt da senha |
| `role` | `ENUM('admin','user')` | Não | `'user'` | Perfil de acesso _(pendente: SPEC-002)_ |
| `created_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP` | Data de criação |
| `updated_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP ON UPDATE` | Data de atualização |

**Índices:** `UNIQUE (email)`, `INDEX (email, role)` _(após SPEC-002)_

---

### `produtos`

Catálogo de produtos disponíveis para venda.

| Coluna | Tipo | Nulo | Padrão | Descrição |
|--------|------|------|--------|-----------|
| `id` | `INT` AUTO_INCREMENT | Não | — | Chave primária |
| `nome` | `VARCHAR(255)` | Não | — | Nome do produto |
| `preco` | `DECIMAL(10,2)` | Não | — | Preço base do produto |
| `descricao` | `TEXT` | Sim | `NULL` | Descrição detalhada |
| `imagem` | `LONGTEXT` | Sim | `NULL` | URL ou base64 da imagem |
| `status` | `TINYINT(1)` | Não | `1` | `1` = ativo, `0` = inativo |
| `created_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP` | Data de criação |
| `updated_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP ON UPDATE` | Data de atualização |

---

### `variacoes`

Variações de um produto (ex: cor, tamanho). Cada variação tem SKU único.

| Coluna | Tipo | Nulo | Padrão | Descrição |
|--------|------|------|--------|-----------|
| `id` | `INT` AUTO_INCREMENT | Não | — | Chave primária |
| `produto_id` | `INT` | Não | — | FK → `produtos.id` |
| `tipo` | `VARCHAR(125)` | Não | — | Tipo da variação (ex: `cor`, `tamanho`) |
| `valor` | `VARCHAR(255)` | Não | — | Valor da variação (ex: `azul`, `M`) |
| `sku` | `VARCHAR(380)` | Não | — | Código único da variação |
| `created_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP` | Data de criação |
| `updated_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP ON UPDATE` | Data de atualização |

**FK:** `produto_id → produtos(id) ON DELETE CASCADE`

---

### `estoque`

Controle de quantidade disponível por variação.

| Coluna | Tipo | Nulo | Padrão | Descrição |
|--------|------|------|--------|-----------|
| `id` | `INT` AUTO_INCREMENT | Não | — | Chave primária |
| `produto_id` | `INT` | Não | — | FK → `produtos.id` |
| `variacao_id` | `INT` | Não | — | FK → `variacoes.id` (único) |
| `quantidade` | `INT` | Não | — | Quantidade disponível |
| `created_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP` | Data de criação |
| `updated_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP ON UPDATE` | Data de atualização |

**FK:** `variacao_id → variacoes(id) ON DELETE CASCADE`, `produto_id → produtos(id) ON DELETE CASCADE`  
**Índices:** `UNIQUE (variacao_id)`, `INDEX (produto_id)`, `INDEX (variacao_id)`

---

### `cupons`

Códigos promocionais de desconto.

| Coluna | Tipo | Nulo | Padrão | Descrição |
|--------|------|------|--------|-----------|
| `id` | `INT` AUTO_INCREMENT | Não | — | Chave primária |
| `codigo` | `VARCHAR(255)` | Não | — | Código único do cupom |
| `desconto` | `DECIMAL(10,2)` | Não | — | Valor ou percentual de desconto |
| `valor_minimo` | `DECIMAL(10,2)` | Não | — | Valor mínimo do pedido para uso |
| `quantidade` | `INT` | Não | — | Quantidade de usos disponíveis |
| `validade` | `TIMESTAMP` | Não | — | Data de expiração |
| `ativo` | `BOOLEAN` | Não | `TRUE` | Se o cupom está ativo |
| `created_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP` | Data de criação |
| `updated_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP ON UPDATE` | Data de atualização |

**Índices:** `UNIQUE (codigo)`

---

### `pedidos`

Pedidos finalizados pelo checkout.

| Coluna | Tipo | Nulo | Padrão | Descrição |
|--------|------|------|--------|-----------|
| `id` | `INT` AUTO_INCREMENT | Não | — | Chave primária |
| `user_id` | `INT` | Não | — | Referência ao usuário (sem FK formal) |
| `total` | `DECIMAL(10,2)` | Não | — | Valor total do pedido |
| `status` | `VARCHAR(50)` | Não | — | Status atual (`pendente`, `pago`, `cancelado`) |
| `created_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP` | Data de criação |
| `updated_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP ON UPDATE` | Data de atualização |

---

### `configurations` _(pendente: SPEC-001)_

Configurações da aplicação em formato chave-valor.

| Coluna | Tipo | Nulo | Padrão | Descrição |
|--------|------|------|--------|-----------|
| `id` | `INT` AUTO_INCREMENT | Não | — | Chave primária |
| `key` | `VARCHAR(255)` | Não | — | Chave da configuração (único) |
| `value` | `TEXT` | Sim | `NULL` | Valor da configuração |
| `created_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP` | Data de criação |
| `updated_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP ON UPDATE` | Data de atualização |

**Seeds:** `active_template = 'default'`

---

### `login_attempts` _(pendente: SPEC-002)_

Registro de tentativas de login para rate limiting.

| Coluna | Tipo | Nulo | Padrão | Descrição |
|--------|------|------|--------|-----------|
| `id` | `INT` AUTO_INCREMENT | Não | — | Chave primária |
| `email` | `VARCHAR(255)` | Não | — | Email usado na tentativa |
| `ip` | `VARCHAR(45)` | Não | — | IP da requisição (suporta IPv6) |
| `attempted_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP` | Data/hora da tentativa |

**Índices:** `INDEX (email, attempted_at)`, `INDEX (ip, attempted_at)`

---

### `password_resets` _(pendente: SPEC-002)_

Tokens para recuperação de senha.

| Coluna | Tipo | Nulo | Padrão | Descrição |
|--------|------|------|--------|-----------|
| `id` | `INT` AUTO_INCREMENT | Não | — | Chave primária |
| `user_id` | `INT` | Não | — | FK → `users.id` |
| `token` | `VARCHAR(64)` | Não | — | Token único de recuperação |
| `expires_at` | `TIMESTAMP` | Não | — | Expiração (criação + 1h) |
| `used_at` | `TIMESTAMP` | Sim | `NULL` | Quando foi usado (de uso único) |
| `created_at` | `TIMESTAMP` | Não | `CURRENT_TIMESTAMP` | Data de criação |

**FK:** `user_id → users(id) ON DELETE CASCADE`  
**Índices:** `UNIQUE (token)`, `INDEX (token)`

---

## Convenções

- Todas as tabelas têm `created_at` e `updated_at`.
- FKs usam `ON DELETE CASCADE` por padrão.
- Nomes de tabelas em português (alinhado com as URLs amigáveis — `coding-conventions.md` Regra 8).
- Novas tabelas devem ter migration em `database/migrations/` antes de alterar `schema.sql`.
