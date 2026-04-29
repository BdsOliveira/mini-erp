# Visão Geral da Arquitetura (Boilerplate Modular)

Este documento descreve a arquitetura modular deste projeto, que serve como um **boilerplate flexível** para o desenvolvimento rápido de diversas aplicações PHP.

## 1. Estrutura Modular (`app/Modules/`)
A aplicação é dividida em contextos de negócio independentes (módulos). Cada módulo contém seu próprio ciclo de vida (Controller, Repository, Service, etc.), garantindo o isolamento e a facilidade de manutenção.

### Módulos Atuais:
- **Catalog (`app/Modules/Catalog/`):** Gestão de produtos, variantes, DTOs e estoque.
- **Cart (`app/Modules/Cart/`):** Lógica do carrinho de compras e serviços de dados do carrinho.
- **Checkout (`app/Modules/Checkout/`):** Processamento de pedidos, integrações de pagamento (webhooks) e notificações.
- **Store (`app/Modules/Store/`):** Controladores da vitrine pública e interface com o cliente.
- **Core (`app/Modules/Core/`):** Classes base (`BaseController`, `BaseRepository`) e controladores essenciais (Home, Erros).
- **Support (`app/Modules/Support/`):** Serviços utilitários transversais como cálculo de frete e validação de cupons.

## 2. O Framework (`packages/framework/`)
O núcleo do sistema utiliza um framework minimalista:
- **Bootstrap (`Application.php`):** Inicialização global.
- **Roteamento Dinâmico:** O arquivo `routes/web.php` atua como um agregador automático. Ele percorre todos os módulos em `app/Modules/` e carrega os arquivos `routes.php` individuais.
- **View Engine:** Renderização via Traits no `BaseController`.

## 3. Padrões de Cada Módulo
Para cada novo módulo (ex: `Scheduling`), deve-se seguir a estrutura:
```text
app/Modules/ModuleName/
├── Controllers/   # Lógica de entrada/saída HTTP
├── Repositories/  # Abstração de banco de dados
├── Services/      # Regras de negócio complexas
├── Models/        # Entidades do banco
├── DTOs/          # Objetos de transferência de dados
└── routes.php     # Definições de rotas do módulo (GET/POST)
```

## 4. Funcionamento das Rotas
Cada módulo deve retornar um array associativo em seu `routes.php`:
```php
return [
    "GET" => [ "/url" => [Controller::class, "metodo"] ],
    "POST" => [ "/url" => [Controller::class, "metodo"] ]
];
```
O agregador central mescla esses arrays, garantindo que o sistema de roteamento do framework receba a lista completa de endpoints ativos.

## 5. Convenções
- **Isolamento:** Um módulo deve preferencialmente interagir com outro através de Services ou Repositories bem definidos.
- **Namespaces:** Seguem o padrão `App\Modules\[Contexto]\[Camada]`.
- **Extensões:** Todos os Controllers herdam de `App\Modules\Core\Base\BaseController`.
