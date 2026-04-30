# ADR-002: Single Action Controllers, Services e Repositories

- **Status:** `Accepted`
- **Data:** 2026-04-29
- **Autores:** Bruno Oliveira

---

## Contexto

Em projetos PHP tradicionais, é comum ter controllers e services com múltiplos métodos agrupados por recurso (ex: `ProductController` com `index`, `create`, `store`, `edit`, `update`, `destroy`). Conforme o projeto cresce, esses arquivos acumulam responsabilidades e tornam-se difíceis de manter, testar e entender.

## Decisão

Toda classe nas camadas de Controller, Service e Repository deve ter **uma única responsabilidade** e um **único método de entrada público** (`execute()`). A nomenclatura da classe deve refletir explicitamente essa responsabilidade:

- **Controllers:** `CreateProductController`, `ListOrdersController`, `UpdateStockController`
- **Services:** `ProcessCheckoutService`, `ValidatePasswordService`, `CalculateShippingService`
- **Repositories:** `FindProductByIdRepository`, `SaveOrderRepository`, `CountLoginAttemptsRepository`

```php
// Correto
final class CreateProductController extends BaseController
{
    public function execute(): void
    {
        // única responsabilidade: criar produto
    }
}

// Errado
final class ProductController extends BaseController
{
    public function index(): void { ... }
    public function create(): void { ... }
    public function store(): void { ... }
    public function destroy(): void { ... }
}
```

## Alternativas Consideradas

| Alternativa | Motivo da Rejeição |
|-------------|-------------------|
| Controllers por recurso (CRUD agrupado) | Classes crescem indefinidamente; difícil testar métodos isoladamente |
| Invokable Controllers (`__invoke`) | `execute()` foi preferido por ser mais explícito e autodocumentado |
| Actions em diretório separado | Adiciona camada sem benefício claro no contexto atual |

## Consequências

### Positivas
- Cada classe tem escopo definido: fácil de ler, testar e substituir.
- Nomes expressivos tornam a navegação no código intuitiva.
- Testes unitários são diretos: instancia, chama `execute()`, valida resultado.
- Facilita Single Responsibility Principle por design, não por disciplina.

### Negativas / Trade-offs
- Número de arquivos cresce mais rapidamente. Mitigado pela estrutura modular (ADR-001).
- Desenvolvedores vindos de frameworks como Laravel podem estranhar a ausência de controllers de recurso.

## Referências

- `docs/standards/coding-conventions.md` — Regra 5 (Fluxo de Camadas e Ação Única)
