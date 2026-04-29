# Convenções de Código

Este documento define os padrões e boas práticas que devem ser seguidos em todos os projetos baseados neste boilerplate.

## 0. Modularização e Isolamento
Cada funcionalidade deve estar rigorosamente isolada em seu respectivo módulo dentro de `app/Modules/`. É proibido espalhar lógica de um contexto em múltiplos módulos.

## 1. Idioma do Código
- **Nomes de variáveis, métodos e classes:** Devem ser escritos obrigatoriamente em **Inglês**.
- **Comentários técnicos:** Recomendado o uso de Inglês para manter a consistência.

## 2. Separação de Responsabilidades (SQL)
- **Queries SQL:** Nunca devem ser inseridas em Controllers ou Services.
- Elas devem residir exclusivamente em classes especializadas, como **Repositories** ou **Data Mappers** (Regra 6 e 9).

## 3. Tipagem Estrita
Todos os arquivos PHP devem iniciar com a declaração de tipos estritos:
```php
<?php
declare(strict_types=1);
```

## 4. Testes e Definição de "Pronto" (DoD)
- Todas as novas features devem acompanhar seus respectivos **Testes Unitários**.
- Uma funcionalidade **nunca** será considerada pronta ou marcada como concluída sem que seus testes unitários estejam implementados e aprovados.

## 5. Fluxo de Camadas e Ação Única (Single Action)
Todas as classes das camadas de Controller, Service e Repository devem seguir o princípio de **Ação Única**. Isso significa que cada classe deve ser responsável por apenas um comportamento ou tarefa.

- **Correto:** `CreateUserController`, `UpdateProductService`, `GetOrderByIdRepository`. Cada uma com um método principal (ex: `execute()`).
- **Errado:** `UserController` (com métodos create, edit, delete), `ProductService` (com múltiplas lógicas de negócio), `OrderRepository` (com todas as queries de pedidos).

### Controllers (Regra 7)
- Responsáveis apenas por receber requisições HTTP e disparar uma única ação.
- Delegam a execução da lógica para os **Services**.
- Retornam a resposta (View ou JSON).

### Services (Regra 8)
- Contêm uma única **Lógica de Negócio**.
- Não devem interagir diretamente com o banco de dados ou manipular dados brutos de persistência.

### Repositories / Data Mappers (Regra 9)
- Responsáveis por uma única operação de interação com o banco de dados (leitura ou escrita específica).
- **Não** devem conter lógica de negócio.

## 6. Organização de Arquivos (Regra 10)
O código deve seguir rigorosamente a estrutura modularizada definida na arquitetura:
`app/Modules/[Modulo]/[Camada]`

## 7. Specification-Driven Development (Regra SDD)

Toda nova feature deve seguir o processo SDD descrito em [`docs/specs/sdd-process.md`](../specs/sdd-process.md).

- Nenhum código de feature é escrito sem uma spec em status `Approved`.
- O template para novas specs está em [`docs/specs/spec-template.md`](../specs/spec-template.md).
- Specs ficam em `docs/specs/features/SPEC-{número}-{slug}.md`.

## 8. Rotas e URLs (Regra 11)
Ao contrário do código interno, as interfaces externas (URLs) devem ser amigáveis ao usuário local:
- **Paths e Queries:** Devem ser sempre escritos em **Português**.
- Exemplo: `/produtos/editar?id=1` (Correto) | `/products/edit?id=1` (Incorreto)
