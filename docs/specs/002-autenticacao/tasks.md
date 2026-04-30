# [SPEC-002] Tasks de Implementação (Refinada)

- **Spec:** [spec.md](./spec.md)
- **Status:** `Pending`
- **Criado em:** 2026-04-29

---

## Etapa 1 — Banco de Dados e Seed

- [ ] Criar `database/migrations/alter_users_add_role.sql` — adiciona coluna `role ENUM('admin', 'user') NOT NULL DEFAULT 'user'` e índice `idx_email_role (email, role)` na tabela `users`.
- [ ] Criar `database/migrations/create_login_attempts_table.sql` — tabela `login_attempts` (`id`, `email`, `ip`, `attempted_at`).
- [ ] Criar `database/migrations/create_password_resets_table.sql` — tabela `password_resets` (`id`, `user_id` FK→users, `token VARCHAR(64) UNIQUE`, `expires_at`, `used_at`, `created_at`).
- [ ] Criar `database/seeds/admin_user.php` — script PHP que lê `ADMIN_PASSWORD` do `.env`, gera o hash via `password_hash(PASSWORD_BCRYPT, ['cost' => 12])` e executa `INSERT INTO users (name, email, password, role) VALUES ('Administrador', 'admin@sistema.com', '<hash>', 'admin')`.
- [ ] Aplicar todas as migrations e atualizar `database/schema.sql`.

---

## Etapa 2 — Módulo Auth (Infraestrutura Transversal)

- [ ] Criar `app/Modules/Auth/Services/ValidatePasswordService.php` — valida política de senha (mínimo 8 chars, 1 maiúscula, 1 número); retorna `bool`. Deve ser testável unitariamente.
- [ ] Criar `app/Modules/Auth/Services/CsrfService.php` — `generate(): string` (cria token com `bin2hex(random_bytes(32))` e salva em `$_SESSION['csrf_token']`); `validate(string $token): bool` (compara com `hash_equals` para evitar timing attacks).
- [ ] Criar `app/Modules/Auth/Services/RateLimitService.php` — `isBlocked(string $email, string $ip): bool` (verifica ≥5 tentativas nos últimos 15 min); `record(string $email, string $ip): void` (insere em `login_attempts`). Usa `CountLoginAttemptsRepository` e `SaveLoginAttemptRepository`.
- [ ] Criar `app/Modules/Auth/Repositories/FindUserByEmailRepository.php` — busca um usuário por `email` e `role`; retorna array com os dados ou `null`.
- [ ] Criar `app/Modules/Auth/Repositories/SaveLoginAttemptRepository.php` — insere registro em `login_attempts`.
- [ ] Criar `app/Modules/Auth/Repositories/CountLoginAttemptsRepository.php` — conta tentativas por `email + ip` na janela de 15 minutos.
- [ ] Criar `app/Modules/Auth/Repositories/SavePasswordResetRepository.php` — insere ou atualiza token em `password_resets`.
- [ ] Criar `app/Modules/Auth/Repositories/FindPasswordResetByTokenRepository.php` — busca token válido (`expires_at > now()` e `used_at IS NULL`); retorna dados ou `null`.
- [ ] Criar `app/Modules/Auth/Guards/AuthGuard.php` — classe estática com `checkAdmin(): void` e `checkUser(): void`; cada método verifica `auth.user_id`, `auth.role` e `auth.expires_at` na sessão; renova `expires_at` (sliding session +8h) em caso de sucesso; chama `Session::destroy()` + `redirect` em caso de falha.
- [ ] Criar `app/Modules/Auth/routes.php` — vazio por ora (as rotas de admin e account ficam nos seus respectivos módulos).

---

## Etapa 3 — DTOs de Auth

- [ ] Criar `app/Modules/Account/DTOs/LoginDTO.php` — `readonly class` com `email`, `password`, `csrfToken`; construtor recebe `array $data`.
- [ ] Criar `app/Modules/Account/DTOs/RegisterDTO.php` — `readonly class` com `name`, `email`, `password`, `passwordConfirmation`, `csrfToken`.
- [ ] Criar `app/Modules/Auth/DTOs/ForgotPasswordDTO.php` — `readonly class` com `email`, `csrfToken`.
- [ ] Criar `app/Modules/Auth/DTOs/ResetPasswordDTO.php` — `readonly class` com `token`, `password`, `passwordConfirmation`, `csrfToken`.

---

## Etapa 4 — Módulo Account (Usuário Final)

- [ ] Criar `app/Modules/Account/Models/User.php` — `readonly class` com `id`, `name`, `email`, `role`.
- [ ] Criar `app/Modules/Account/Repositories/CreateUserRepository.php` — insere usuário na tabela `users` com `role = 'user'`.
- [ ] Criar `app/Modules/Account/Repositories/UpdateUserPasswordRepository.php` — atualiza `password` do usuário por `id`, marca token como `used_at = now()` em `password_resets`.
- [ ] Criar `app/Modules/Account/Services/RegisterUserService.php` — orquestra: valida DTO, verifica unicidade do email (via `FindUserByEmailRepository`), hasheia senha, chama `CreateUserRepository`; retorna o `User` criado.
- [ ] Criar `app/Modules/Account/Controllers/ShowRegisterController.php` — `execute()` renderiza view de cadastro com CSRF token.
- [ ] Criar `app/Modules/Account/Controllers/RegisterController.php` — `execute()` instancia `RegisterDTO`, chama `RegisterUserService`, cria sessão (`auth.user_id`, `auth.role = 'user'`, `auth.expires_at`), redireciona para `/`.
- [ ] Criar `app/Modules/Account/Controllers/ShowLoginController.php` — `execute()` renderiza view de login com CSRF token e flash.
- [ ] Criar `app/Modules/Account/Controllers/LoginController.php` — `execute()` instancia `LoginDTO`, valida CSRF, chama `RateLimitService::isBlocked()`, valida credenciais (`FindUserByEmailRepository` + `password_verify` + `role = 'user'`), registra tentativa em caso de falha, cria sessão em caso de sucesso, redireciona para `/`.
- [ ] Criar `app/Modules/Account/Controllers/LogoutController.php` — `execute()` remove apenas chaves `auth.*` da sessão (preserva carrinho e outros dados), redireciona para `/conta/login` com flash.
- [ ] Criar `app/Modules/Account/Controllers/ShowForgotPasswordController.php` — renderiza formulário de recuperação.
- [ ] Criar `app/Modules/Account/Controllers/ForgotPasswordController.php` — valida CSRF, busca usuário por email com `role = 'user'`, gera token, salva em `password_resets`, envia email com link `/conta/redefinir-senha?token=<token>`, exibe mensagem genérica.
- [ ] Criar `app/Modules/Account/Controllers/ShowResetPasswordController.php` — valida token via `FindPasswordResetByTokenRepository`, renderiza formulário ou exibe erro se inválido/expirado.
- [ ] Criar `app/Modules/Account/Controllers/ResetPasswordController.php` — valida CSRF, valida token, valida política de senha, atualiza senha via `UpdateUserPasswordRepository`, redireciona para `/conta/login`.
- [ ] Criar `app/Modules/Account/routes.php` — registra todas as rotas `/conta/*` conforme tabela da seção 5.1 da spec.

---

## Etapa 5 — Módulo Admin (Autenticação Administrativa)

- [ ] Criar `app/Modules/Admin/Controllers/ShowLoginController.php` — renderiza view de login do admin com CSRF token e flash.
- [ ] Criar `app/Modules/Admin/Controllers/LoginController.php` — valida CSRF, chama `RateLimitService::isBlocked()`, valida credenciais (`FindUserByEmailRepository` + `password_verify` + `role = 'admin'`), registra tentativa em falha, cria sessão `auth.*` em sucesso, redireciona para `/admin`.
- [ ] Criar `app/Modules/Admin/Controllers/LogoutController.php` — chama `Session::clear()` + `session_destroy()`, redireciona para `/admin/login` com flash.
- [ ] Criar `app/Modules/Admin/Controllers/ShowForgotPasswordController.php` — renderiza formulário de recuperação de senha do admin.
- [ ] Criar `app/Modules/Admin/Controllers/ForgotPasswordController.php` — idem ao Account, mas busca `role = 'admin'` e usa link `/admin/redefinir-senha?token=<token>`.
- [ ] Criar `app/Modules/Admin/Controllers/ShowResetPasswordController.php` — valida token, renderiza formulário.
- [ ] Criar `app/Modules/Admin/Controllers/ResetPasswordController.php` — valida CSRF, token e política de senha, atualiza senha, redireciona para `/admin/login`.
- [ ] Adicionar chamada `AuthGuard::checkAdmin()` no início de `execute()` em **todos os controllers admin existentes** (`ListProductsController`, `StoreProductController`, `EditProductController`, `UpdateProductController`, `ListProductVariantsController`, `StoreProductVariantsController`, `EditProductVariantController`, `UpdateProductVariantController`, `ListOrdersController`, `SchedulingController`).
- [ ] Adicionar rotas `/admin/login`, `/admin/logout`, `/admin/esqueci-senha` e `/admin/redefinir-senha` ao `app/Modules/Admin/routes.php` (criar o arquivo se não existir, ou adicionar ao Catalog/routes.php — verificar estrutura atual e criar módulo Admin dedicado se necessário).

---

## Etapa 6 — Views (Twig)

- [ ] Criar `resources/views/admin/login.php` — formulário de login com campo CSRF hidden, exibição de flash (erro/sucesso).
- [ ] Criar `resources/views/admin/esqueci-senha.php` — formulário de recuperação de senha.
- [ ] Criar `resources/views/admin/redefinir-senha.php` — formulário de nova senha (com campos `password` e `password_confirmation`).
- [ ] Criar `resources/views/conta/cadastro.php` — formulário de registro com flash de validação.
- [ ] Criar `resources/views/conta/login.php` — formulário de login com flash.
- [ ] Criar `resources/views/conta/esqueci-senha.php` — formulário de recuperação.
- [ ] Criar `resources/views/conta/redefinir-senha.php` — formulário de nova senha.
- [ ] Adicionar link "Sair" no layout do admin que faz `POST /admin/logout` (necessário pois logout deve ser POST para evitar CSRF via GET).

---

## Etapa 7 — Testes Unitários

- [ ] Criar `tests/Unit/Auth/ValidatePasswordServiceTest.php` — cobre: senha válida, senha curta (< 8 chars), sem maiúscula, sem número, combinações inválidas.
- [ ] Criar `tests/Unit/Auth/CsrfServiceTest.php` — cobre: geração de token (64 chars hex), validação correta, validação com token errado.
- [ ] Criar `tests/Unit/Auth/RateLimitServiceTest.php` — cobre: não bloqueado (0 tentativas), não bloqueado (4 tentativas), bloqueado (5 tentativas em 15 min), desbloqueado após janela.

---

## Etapa 8 — Validação e DoD

- [ ] Verificar que todas as rotas admin retornam `302 /admin/login` quando acessadas sem sessão.
- [ ] Verificar que login com credenciais erradas 5x bloqueia por 30 min.
- [ ] Verificar que sessão expira após 8h de inatividade (sliding session renovada a cada request).
- [ ] Verificar que logout do admin destrói sessão completamente (carrinho deve ser zerado).
- [ ] Verificar que logout do usuário final preserva dados de sessão da aplicação (ex: itens do carrinho).
- [ ] Verificar que token de reset é de uso único (segunda tentativa retorna erro).
- [ ] Verificar que política de senha é validada no cadastro e no reset.
- [ ] Rodar `./vendor/bin/pest` — todos os testes devem passar (incluindo os novos).
- [ ] Rodar `./vendor/bin/phpstan analyse` — sem erros de tipagem.
