# Estratégia de Testes

Este documento define como os testes são organizados, nomeados e executados no projeto. É complementar ao `coding-conventions.md` (Regra 4 — DoD de Testes).

---

## Framework

O projeto usa **[Pest PHP](https://pestphp.com/)** (v4) como framework de testes, rodando sobre PHPUnit.

- Runner: `vendor/bin/pest` (alias: `composer pest`)
- Configuração do runner: `phpunit.xml`
- Bootstrap e configurações globais: `tests/Pest.php`
- Classe base: `tests/TestCase.php`

---

## Filosofia

Testes existem para verificar comportamento, não implementação. Um teste bem escrito documenta o que o sistema deve fazer e garante que mudanças futuras não quebrem comportamentos existentes.

> **Regra:** Nenhuma feature vai para `Implemented` sem testes passando. Ver `definition-of-done.md`.

---

## Tipos de Teste

### 1. Testes Unitários

Verificam uma única unidade de código em isolamento. Dependências são substituídas por doubles (mocks/stubs via PHPUnit, acessíveis dentro do Pest).

**O que testar:**
- `Services` — lógica de negócio
- `Repositories` — consultas SQL (com mock do PDO ou banco em memória)
- Funções auxiliares (helpers, validators)

**O que não testar:**
- Controllers (use testes de Feature)
- Views/Templates

**Localização:** `tests/Unit/{Modulo}/`

**Exemplo:**
```php
// tests/Unit/Auth/ValidatePasswordServiceTest.php

use App\Modules\Auth\Services\ValidatePasswordService;

test('valid password passes', function () {
    $service = new ValidatePasswordService();

    expect($service->execute('Senha123'))->toBeTrue();
});

test('password without uppercase fails', function () {
    $service = new ValidatePasswordService();

    expect($service->execute('senha123'))->toBeFalse();
});

test('password too short fails', function () {
    $service = new ValidatePasswordService();

    expect($service->execute('Ab1'))->toBeFalse();
});
```

**Agrupando casos com `dataset`:**
```php
// Equivalente a múltiplos testes com diferentes entradas
dataset('invalid passwords', [
    'too short'          => ['Ab1'],
    'no uppercase'       => ['senha123'],
    'no number'          => ['SenhaForte'],
    'only numbers'       => ['12345678'],
]);

test('invalid passwords fail validation', function (string $password) {
    $service = new ValidatePasswordService();

    expect($service->execute($password))->toBeFalse();
})->with('invalid passwords');
```

---

### 2. Testes de Integração

Verificam a interação entre múltiplas camadas (ex: Service + Repository + banco de dados real ou em memória).

**O que testar:**
- Fluxos completos de um caso de uso
- Resolvers que interagem com banco (ex: `DatabaseTemplateResolver`)

**Localização:** `tests/Unit/{Modulo}/` (integração leve) ou `tests/Feature/{Modulo}/` (com HTTP)

---

### 3. Testes de Feature

Verificam o comportamento do sistema pela perspectiva HTTP (request → response). Usam o `TestCase` customizado definido em `tests/Pest.php` via `pest()->extend(TestCase::class)->in('Feature')`.

**Localização:** `tests/Feature/{Modulo}/`

> Testes de Feature são opcionais para o DoD básico, mas recomendados para fluxos críticos (checkout, autenticação).

---

## Estrutura de Diretórios

```
tests/
├── Pest.php                                   ← Bootstrap: datasets globais, helpers, extends
├── TestCase.php                               ← Classe base (estende PHPUnit\TestCase)
├── Unit/
│   ├── Auth/
│   │   ├── ValidatePasswordServiceTest.php
│   │   ├── RateLimitServiceTest.php
│   │   └── CsrfServiceTest.php
│   ├── Support/
│   │   └── CalculateShippingServiceTest.php
│   └── View/
│       ├── DatabaseTemplateResolverTest.php
│       └── AssetHelperTest.php
└── Feature/
    ├── ExampleTest.php
    └── Auth/
        └── LoginFeatureTest.php
```

---

## Convenções de Nomenclatura

### Arquivos

```
{NomeDaClasseTestada}Test.php
```

Exemplos:
- `ValidatePasswordServiceTest.php`
- `FindProductByIdRepositoryTest.php`
- `AssetHelperTest.php`

### Descrição dos testes

Use frases descritivas em inglês, no formato:

```
{contexto} {comportamento esperado}
```

```php
test('resolver returns default template when active template directory not found', function () { ... });
test('asset helper falls back to default when file does not exist in active template', function () { ... });
test('login is blocked after 5 failed attempts within 15 minutes', function () { ... });
```

Evite prefixos desnecessários como `it_` ou `test_` — o Pest já trata a string como descrição legível.

---

## Execução

```bash
# Todos os testes
composer pest
# ou diretamente:
vendor/bin/pest

# Somente testes unitários
vendor/bin/pest tests/Unit/

# Filtrar por nome
vendor/bin/pest --filter "ValidatePasswordService"

# Com cobertura (requer Xdebug ou PCOV)
vendor/bin/pest --coverage

# Parar no primeiro erro
vendor/bin/pest --bail

# Execução paralela
vendor/bin/pest --parallel
```

Aliases disponíveis no `composer.json`:

```bash
composer pest    # roda os testes
composer verify  # roda pest + phpstan
```

---

## Cobertura Mínima por Camada

| Camada | Cobertura Mínima | Tipo de Teste |
|--------|-----------------|---------------|
| Services | 100% dos métodos públicos | Unitário |
| Repositories | Happy path + cenários de erro | Unitário / Integração |
| Helpers / Functions | 100% dos branches | Unitário |
| Controllers | Não obrigatório no DoD básico | Feature (opcional) |

---

## Mocks e Stubs

O Pest usa os recursos nativos do PHPUnit para doubles. Acesse-os dentro dos testes via `$this` (quando usar `TestCase`) ou via funções globais:

```php
// Stub simples
test('service handles empty result from repository', function () {
    $repository = $this->createStub(FindUserByEmailRepository::class);
    $repository->method('execute')->willReturn(null);

    $service = new LoginService($repository);

    expect($service->execute('test@test.com', 'senha'))->toBeFalse();
});

// Mock com verificação de chamada
test('failed login attempt is recorded', function () {
    $attemptRepository = $this->createMock(SaveLoginAttemptRepository::class);
    $attemptRepository->expects($this->once())->method('execute');

    $service = new LoginService(
        userRepository: $this->createStub(FindUserByEmailRepository::class),
        attemptRepository: $attemptRepository,
    );

    $service->execute('test@test.com', 'senha_errada');
});
```

> Para testes de Feature, o `TestCase` customizado em `tests/TestCase.php` é aplicado automaticamente via `pest()->extend(TestCase::class)->in('Feature')` no `Pest.php`.

---

## Helpers e Funções Globais

Funções reutilizáveis entre testes podem ser definidas em `tests/Pest.php`:

```php
// tests/Pest.php

function makeUser(array $overrides = []): array
{
    return array_merge([
        'id'       => 1,
        'email'    => 'test@example.com',
        'role'     => 'user',
        'password' => password_hash('Senha123', PASSWORD_BCRYPT),
    ], $overrides);
}
```

Uso nos testes:
```php
test('admin user has correct role', function () {
    $user = makeUser(['role' => 'admin']);

    expect($user['role'])->toBe('admin');
});
```

---

## Boas Práticas

1. **Arrange, Act, Assert (AAA):** organize cada teste em três blocos claros.
2. **Um comportamento por teste:** testes com múltiplos `expect` independentes são difíceis de diagnosticar.
3. **Descrições que documentam:** a string do `test()` deve ser autoexplicativa sem precisar ler o corpo.
4. **Sem lógica nos testes:** sem condicionais ou loops — use `dataset()` para múltiplos inputs.
5. **Dados isolados:** cada teste cria e limpa seus próprios dados. Use `beforeEach` / `afterEach` para setup e teardown.

```php
beforeEach(function () {
    $this->service = new ValidatePasswordService();
});

test('valid password passes', function () {
    expect($this->service->execute('Senha123'))->toBeTrue();
});

test('short password fails', function () {
    expect($this->service->execute('Ab1'))->toBeFalse();
});
```
