# [SPEC-002] Autenticação do Sistema

- **Status:** `Draft`
- **Módulo:** `app/Modules/Auth/`, `app/Modules/Account/`, `app/Modules/Admin/`
- **Criado em:** 2026-04-29
- **Autor:** Bruno Oliveira

---

## 1. Contexto e Problema

O sistema atualmente não possui nenhuma camada de autenticação. O painel administrativo (`/admin`, `/produtos`, `/pedidos`, etc.) é acessível publicamente por qualquer pessoa com o endereço da URL. Da mesma forma, não há conceito de "usuário autenticado" na aplicação, resultando em operações sem vínculo real com um usuário (`user_id` é gerado com `random_int`).

Este projeto é um **boilerplate modular** para construção de diversas aplicações PHP — não exclusivamente e-commerce. A autenticação deve, portanto, ser implementada de forma genérica e reutilizável.

Esta spec cobre a implementação de dois sistemas de autenticação independentes:
1. **Admin Auth** — protege o painel administrativo, restrito a operadores do sistema.
2. **User Auth** — permite que usuários finais da aplicação criem conta e façam login. O papel (`role`) desses usuários é genérico; módulos específicos da aplicação (ex: Checkout, Store) são responsáveis por atribuir significado de negócio a esse usuário autenticado.

---

## 2. Objetivo

Implementar autenticação segura com dois perfis distintos (admin e usuário final), usando PHP Sessions, com proteção contra força bruta, política de senha robusta e recuperação de senha via email. A implementação deve ser genérica o suficiente para servir qualquer aplicação construída sobre este boilerplate.

---

## 3. Casos de Uso

### UC-01: Login de Administrador

- **Ator:** Administrador
- **Pré-condições:** Usuário admin existe no banco (inserido via seed). Usuário não está autenticado.
- **Fluxo Principal:**
  1. Admin acessa qualquer rota protegida (ex: `/admin`).
  2. Sistema detecta ausência de sessão autenticada e redireciona para `/admin/login`.
  3. Admin preenche email e senha e submete o formulário.
  4. Sistema valida credenciais: busca usuário por email, verifica `role = 'admin'`, compara senha com `password_verify`.
  5. Sistema verifica se a conta não está bloqueada por tentativas excessivas.
  6. Sistema cria sessão autenticada com `user_id`, `role` e `expires_at` (now + 8h).
  7. Sistema redireciona para `/admin`.
- **Fluxo Alternativo A — Credenciais inválidas:**
  - A1: Sistema incrementa contador de tentativas falhas para aquele email/IP.
  - A2: Exibe mensagem genérica: "Email ou senha incorretos."
  - A3: Após 5 tentativas em 15 minutos, bloqueia login por 30 minutos.
  - A4: Exibe mensagem: "Conta temporariamente bloqueada. Tente novamente mais tarde."
- **Fluxo Alternativo B — Sessão expirada:**
  - B1: Admin tenta acessar rota protegida com sessão expirada.
  - B2: Sistema destrói a sessão e redireciona para `/admin/login` com mensagem flash de sessão expirada.
- **Pós-condições:** Admin está autenticado. Sessão armazena `auth.user_id`, `auth.role = 'admin'`, `auth.expires_at`.

---

### UC-02: Logout de Administrador

- **Ator:** Administrador autenticado
- **Pré-condições:** Admin está logado.
- **Fluxo Principal:**
  1. Admin clica em "Sair" no painel.
  2. Sistema destrói a sessão PHP (`Session::clear()` + `session_destroy()`).
  3. Sistema redireciona para `/admin/login` com mensagem flash "Sessão encerrada com sucesso."
- **Pós-condições:** Sessão destruída. Admin redirecionado para o login.

---

### UC-03: Proteção de Rotas Administrativas (Auth Guard)

- **Ator:** Sistema
- **Pré-condições:** Requisição chega a qualquer rota do grupo `/admin`, `/produtos`, `/pedidos`, `/agendamento`.
- **Fluxo Principal:**
  1. `AuthGuard::checkAdmin()` é chamado no início do `execute()` de cada controller protegido.
  2. Sistema verifica existência de `auth.user_id` na sessão.
  3. Sistema verifica `auth.role === 'admin'`.
  4. Sistema verifica `auth.expires_at > now()`.
  5. Acesso liberado. Sistema renova `expires_at` (sliding session).
- **Fluxo Alternativo:**
  - A1: Qualquer verificação falha → destrói sessão e redireciona para `/admin/login`.
- **Pós-condições:** Acesso liberado ou redirecionado.

---

### UC-04: Recuperação de Senha do Admin

- **Ator:** Administrador
- **Pré-condições:** Admin possui email cadastrado.
- **Fluxo Principal:**
  1. Admin acessa `/admin/esqueci-senha`.
  2. Preenche email e submete.
  3. Sistema busca usuário com aquele email e `role = 'admin'`.
  4. Sistema gera token seguro com `bin2hex(random_bytes(32))`, salva em `password_resets` com `expires_at = now() + 1h`.
  5. Sistema envia email com link: `/admin/redefinir-senha?token=<token>`.
  6. Sistema exibe mensagem genérica (mesmo se email não encontrado): "Se o email estiver cadastrado, você receberá as instruções."
  7. Admin clica no link do email.
  8. Sistema valida token: busca em `password_resets`, verifica `expires_at > now()` e `used_at IS NULL`.
  9. Admin preenche nova senha (e confirmação).
  10. Sistema valida a política de senha. Salva senha com `password_hash(..., PASSWORD_BCRYPT, ['cost' => 12])`. Marca token como `used_at = now()`.
  11. Sistema redireciona para `/admin/login` com mensagem de sucesso.
- **Fluxo Alternativo — Token inválido ou expirado:**
  - A1: Exibe mensagem: "Este link é inválido ou expirou. Solicite um novo."
- **Pós-condições:** Senha atualizada. Token invalidado. Admin deve fazer login com a nova senha.

---

### UC-05: Registro de Usuário Final

- **Ator:** Usuário (visitante não autenticado)
- **Pré-condições:** Email ainda não cadastrado com `role = 'user'`.
- **Fluxo Principal:**
  1. Usuário acessa `/conta/cadastro`.
  2. Preenche nome, email, senha e confirmação de senha.
  3. Sistema valida campos (obrigatoriedade, formato de email, política de senha, senhas iguais).
  4. Sistema verifica unicidade do email (apenas entre `role = 'user'`).
  5. Sistema cria usuário com `password_hash(..., PASSWORD_BCRYPT, ['cost' => 12])` e `role = 'user'`.
  6. Sistema cria sessão autenticada.
  7. Sistema redireciona para `/` com mensagem de boas-vindas.
- **Fluxo Alternativo — Email já cadastrado:**
  - A1: Exibe: "Este email já está cadastrado. Faça login."
- **Pós-condições:** Conta criada. Usuário autenticado na sessão com `auth.role = 'user'`.

---

### UC-06: Login de Usuário Final

- **Ator:** Usuário cadastrado
- **Pré-condições:** Usuário possui conta ativa com `role = 'user'`.
- **Fluxo Principal:**
  1. Usuário acessa `/conta/login`.
  2. Preenche email e senha.
  3. Sistema valida credenciais com `password_verify`, verifica `role = 'user'`.
  4. Sistema aplica rate limiting (mesmas regras do admin).
  5. Sistema cria sessão: `auth.user_id`, `auth.role = 'user'`, `auth.expires_at`.
  6. Sistema redireciona para `/` ou para a rota de origem (se havia tentativa de acesso a rota protegida).
- **Pós-condições:** Usuário autenticado. Sessão criada com duração de 8h.

---

### UC-07: Logout de Usuário Final

- **Ator:** Usuário autenticado
- **Pré-condições:** Usuário está logado.
- **Fluxo Principal:**
  1. Usuário clica em "Sair".
  2. Sistema remove as chaves `auth.*` da sessão, preservando quaisquer dados de sessão da aplicação (ex: carrinho, preferências).
  3. Redireciona para `/conta/login`.
- **Pós-condições:** Sessão de auth removida. Dados de sessão específicos da aplicação preservados.

---

### UC-08: Recuperação de Senha do Usuário Final

- **Ator:** Usuário final
- **Pré-condições:** Usuário possui email cadastrado com `role = 'user'`.
- **Fluxo Principal:** Idêntico ao UC-04, com rotas `/conta/esqueci-senha` e `/conta/redefinir-senha`.
- **Pós-condições:** Senha atualizada. Token invalidado.

---

## 4. Regras de Negócio

| ID    | Regra |
|-------|-------|
| RN-01 | Senhas devem ter no mínimo 8 caracteres, conter ao menos 1 letra maiúscula e ao menos 1 número. |
| RN-02 | Senhas devem ser armazenadas com `password_hash(..., PASSWORD_BCRYPT, ['cost' => 12])`. Nunca em texto plano. |
| RN-03 | Após 5 tentativas de login falhas em uma janela de 15 minutos (por email + IP), o acesso é bloqueado por 30 minutos. |
| RN-04 | A mensagem de erro de login deve ser sempre genérica: "Email ou senha incorretos." — nunca revelar se o email existe. |
| RN-05 | A mensagem de recuperação de senha deve ser sempre genérica, independente de o email existir. |
| RN-06 | Tokens de recuperação de senha expiram em 1 hora e são de uso único (marcados como `used_at`). |
| RN-07 | Tokens de recuperação devem ter 64 caracteres hex gerados com `bin2hex(random_bytes(32))`. |
| RN-08 | A sessão PHP deve ter duração máxima de 8 horas (sliding: renovada a cada request autenticado). |
| RN-09 | Admin e Usuário final são perfis (`role`) distintos na mesma tabela `users`. Um usuário com `role = 'user'` não pode acessar rotas administrativas e vice-versa. |
| RN-10 | O logout do usuário final deve remover apenas as chaves `auth.*` da sessão, preservando dados de sessão específicos da aplicação (ex: carrinho, preferências). Cada aplicação concreta pode sobrescrever esse comportamento se necessário. |
| RN-11 | O logout do admin deve destruir a sessão completamente. |
| RN-12 | Todas as rotas administrativas (`/admin`, `/produtos`, `/pedidos`, `/agendamento`) exigem `role = 'admin'` na sessão. |
| RN-13 | O seed inicial deve criar um usuário admin com senha segura definida via variável de ambiente `ADMIN_PASSWORD`. |
| RN-14 | CSRF tokens devem ser gerados e validados em todos os formulários POST de autenticação. |

---

## 5. Contratos de Interface

### 5.1 Rotas HTTP

| Método | Path | Controller |
|--------|------|------------|
| `GET`  | `/admin/login` | `Admin\ShowLoginController` |
| `POST` | `/admin/login` | `Admin\LoginController` |
| `POST` | `/admin/logout` | `Admin\LogoutController` |
| `GET`  | `/admin/esqueci-senha` | `Admin\ShowForgotPasswordController` |
| `POST` | `/admin/esqueci-senha` | `Admin\ForgotPasswordController` |
| `GET`  | `/admin/redefinir-senha` | `Admin\ShowResetPasswordController` |
| `POST` | `/admin/redefinir-senha` | `Admin\ResetPasswordController` |
| `GET`  | `/conta/cadastro` | `Account\ShowRegisterController` |
| `POST` | `/conta/cadastro` | `Account\RegisterController` |
| `GET`  | `/conta/login` | `Account\ShowLoginController` |
| `POST` | `/conta/login` | `Account\LoginController` |
| `POST` | `/conta/logout` | `Account\LogoutController` |
| `GET`  | `/conta/esqueci-senha` | `Account\ShowForgotPasswordController` |
| `POST` | `/conta/esqueci-senha` | `Account\ForgotPasswordController` |
| `GET`  | `/conta/redefinir-senha` | `Account\ShowResetPasswordController` |
| `POST` | `/conta/redefinir-senha` | `Account\ResetPasswordController` |

### 5.2 DTOs

**`Account\LoginDTO`:**
```php
readonly class LoginDTO {
    public string $email;
    public string $password;
    public string $csrfToken;
}
```

**`Account\RegisterDTO`:**
```php
readonly class RegisterDTO {
    public string $name;
    public string $email;
    public string $password;
    public string $passwordConfirmation;
    public string $csrfToken;
}
```

**`Auth\ForgotPasswordDTO`:**
```php
readonly class ForgotPasswordDTO {
    public string $email;
    public string $csrfToken;
}
```

**`Auth\ResetPasswordDTO`:**
```php
readonly class ResetPasswordDTO {
    public string $token;
    public string $password;
    public string $passwordConfirmation;
    public string $csrfToken;
}
```

### 5.3 Respostas

| Ação | Resposta |
|------|----------|
| Login bem-sucedido (admin) | `redirect('/admin')` |
| Login bem-sucedido (usuário final) | `redirect('/')` |
| Login falho | Render do formulário com mensagem flash de erro |
| Logout | `redirect('/[admin\|conta]/login')` com mensagem flash |
| Recuperação solicitada | Render com mensagem genérica de sucesso |
| Reset bem-sucedido | `redirect('/[admin\|conta]/login')` com mensagem flash |

---

## 6. Esquema de Banco de Dados

### Alterações na tabela `users`

```sql
ALTER TABLE users
    ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user' AFTER email,
    ADD INDEX idx_email_role (email, role);
```

### Nova tabela `login_attempts`

```sql
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    ip VARCHAR(45) NOT NULL,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email_attempted (email, attempted_at),
    INDEX idx_ip_attempted (ip, attempted_at)
);
```

### Nova tabela `password_resets`

```sql
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    used_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token)
);
```

### Seed do usuário admin

```sql
-- Executar com a senha definida em ADMIN_PASSWORD (já hasheada via PHP antes de inserir)
INSERT INTO users (name, email, password, role)
VALUES ('Administrador', 'admin@sistema.com', '<bcrypt_hash>', 'admin');
```

> O script de seed deve ler `ADMIN_PASSWORD` do `.env` e gerar o hash via `password_hash` antes de inserir.

---

## 7. Estrutura de Arquivos

```
app/Modules/
├── Auth/
│   ├── Guards/
│   │   └── AuthGuard.php               # checkAdmin() e checkUser()
│   ├── Services/
│   │   ├── ValidatePasswordService.php # Valida política de senha
│   │   ├── RateLimitService.php        # Verifica e registra tentativas
│   │   └── CsrfService.php             # Gera e valida CSRF tokens
│   ├── Repositories/
│   │   ├── FindUserByEmailRepository.php
│   │   ├── SaveLoginAttemptRepository.php
│   │   ├── CountLoginAttemptsRepository.php
│   │   ├── SavePasswordResetRepository.php
│   │   └── FindPasswordResetByTokenRepository.php
│   └── routes.php                      # Rotas de /admin/login, logout, reset
│
├── Admin/
│   └── Controllers/
│       ├── ShowLoginController.php
│       ├── LoginController.php
│       ├── LogoutController.php
│       ├── ShowForgotPasswordController.php
│       ├── ForgotPasswordController.php
│       ├── ShowResetPasswordController.php
│       └── ResetPasswordController.php
│
└── Account/
    ├── Controllers/
    │   ├── ShowRegisterController.php
    │   ├── RegisterController.php
    │   ├── ShowLoginController.php
    │   ├── LoginController.php
    │   ├── LogoutController.php
    │   ├── ShowForgotPasswordController.php
    │   ├── ForgotPasswordController.php
    │   ├── ShowResetPasswordController.php
    │   └── ResetPasswordController.php
    ├── DTOs/
    │   ├── RegisterDTO.php
    │   └── LoginDTO.php
    ├── Models/
    │   └── User.php
    ├── Repositories/
    │   ├── CreateUserRepository.php
    │   └── UpdateUserPasswordRepository.php
    ├── Services/
    │   └── RegisterCustomerService.php
    └── routes.php                      # Rotas de /conta/*
```

---

## 8. Fluxo de Autenticação (Diagrama)

```
Request → Router → Controller::execute()
                        │
                        ▼
                  AuthGuard::check()
                        │
              ┌─────────┴──────────┐
              │ Sessão válida?      │
              │ role correto?       │
              │ não expirou?        │
              └─────────┬──────────┘
                    Não │               Sim
                        ▼               ▼
               redirect(/login)    Renova expires_at
                                   Libera acesso
```

---

## 9. Critérios de Aceite (DoD)

- [ ] Login de admin funciona com email e senha corretos, cria sessão com `role = 'admin'`.
- [ ] Login de usuário final funciona com email e senha corretos, cria sessão com `role = 'user'`.
- [ ] Tentativas inválidas exibem mensagem genérica.
- [ ] Após 5 tentativas em 15 min, login é bloqueado por 30 min.
- [ ] Todas as rotas administrativas redirecionam para `/admin/login` sem sessão válida.
- [ ] Sessão expira após 8 horas de inatividade.
- [ ] Logout do admin destrói a sessão completamente.
- [ ] Logout do usuário final remove apenas chaves `auth.*`, preservando outros dados de sessão da aplicação.
- [ ] Recuperação de senha envia email com link válido por 1 hora.
- [ ] Token de reset é de uso único (marcado como `used_at` após uso).
- [ ] Política de senha validada: mínimo 8 chars, 1 maiúscula, 1 número.
- [ ] CSRF token validado em todos os formulários POST.
- [ ] Senhas armazenadas com `password_hash(PASSWORD_BCRYPT, cost=12)`.
- [ ] Seed SQL cria usuário admin com senha via `ADMIN_PASSWORD` do `.env`.
- [ ] Nenhuma query SQL em Controllers ou Services.
- [ ] Todos os controllers seguem o padrão Single Action (`execute()`).
- [ ] Rotas registradas nos `routes.php` dos módulos respectivos.
- [ ] Testes unitários cobrem `ValidatePasswordService`, `RateLimitService`, `CsrfService`.

---

## 10. Fora de Escopo

- Autenticação via OAuth (Google, GitHub, etc.).
- 2FA / autenticação de dois fatores.
- Múltiplos perfis de admin (RBAC granular) — admin é único perfil por ora.
- Histórico de sessões / gerenciamento de dispositivos.
- Bloqueio permanente de conta (apenas bloqueio temporário de 30 min).
- "Lembrar de mim" / sessão persistente além de 8 horas.
- Área de perfil do usuário (edição de dados, histórico de atividade) — apenas autenticação.
- Lógica de negócio específica por aplicação (ex: vincular pedidos a usuários) — responsabilidade dos módulos de domínio.

---

## 11. Notas e Decisões Técnicas

- **`AuthGuard` como classe estática:** Segue o padrão do framework (`Session`, `Request` são todos estáticos). `checkAdmin()` e `checkUser()` são chamados diretamente no `execute()` de cada controller protegido, sem necessidade de middleware global (o router não tem suporte a middleware).
- **CSRF via sessão:** O token é gerado com `bin2hex(random_bytes(32))`, armazenado em `$_SESSION['csrf_token']`, e validado via comparação com `hash_equals()` para evitar timing attacks.
- **Rate limiting sem Redis:** Implementado em banco de dados (`login_attempts`). Para o volume esperado de um sistema ERP/boilerplate, é suficiente. Consulta por `email + ip` com janela de `now() - 15 minutes`.
- **Sliding session:** A cada request autenticado bem-sucedido, `auth.expires_at` é atualizado para `now() + 8h`. O PHP session também reseta via `session_set_cookie_params`.
- **Separação admin/usuário:** Ambos usam a mesma tabela `users` com `role` ENUM. O guard valida o role explicitamente, impedindo que um usuário acesse o admin mesmo com sessão válida.
- **`role = 'user'` como valor genérico:** O valor `'user'` foi escolhido em vez de `'customer'` para refletir o caráter generalista do boilerplate. Aplicações concretas que precisem de sub-perfis devem criar uma nova spec e estender este módulo.
- **`ADMIN_PASSWORD` via `.env`:** Nunca commitar senhas no repositório. O seed script deve ser executado manualmente após `docker compose up`.
- **Bcrypt cost 12:** Valor padrão seguro para servidores modernos. Pode ser aumentado se a infraestrutura permitir.
