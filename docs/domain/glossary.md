# Glossário de Domínio

Este documento define os termos ubíquos utilizados no projeto. Todos os membros do time — desenvolvimento, produto e negócio — devem usar estes termos consistentemente em specs, código, commits e discussões.

> Regra: se um termo não está aqui, ele não deve ser usado em specs ou código sem antes ser adicionado a este glossário.

---

## A

**Admin**
Operador do sistema com acesso ao painel administrativo (`/admin`). Possui `role = 'admin'` na tabela `users`. Gerencia produtos, pedidos, cupons e configurações do sistema.

**Asset**
Arquivo estático (CSS, JS, imagem) servido pelo servidor. No contexto de templates, assets são resolvidos com fallback para o tema `default` quando não existem no tema ativo. Ver: Template, Fallback de Asset.

---

## C

**Carrinho (Cart)**
Estrutura temporária (armazenada em sessão) que representa os itens que um usuário pretende comprar. O carrinho existe antes de um pedido ser criado.

**Checkout**
Processo de finalização de compra. Converte o carrinho em um pedido confirmado, inclui cálculo de frete, aplicação de cupom e processamento de pagamento.

**Configuração (Configuration)**
Par chave-valor armazenado no banco de dados na tabela `configurations`. Usado para ajustes de comportamento da aplicação sem alteração de código (ex: `active_template`).

**Controller**
Classe PHP Single Action responsável por receber uma requisição HTTP, orquestrar a execução via Service e retornar uma resposta. Nunca acessa o banco diretamente. Exemplo: `CreateProductController`.

**Cupom**
Código promocional que concede desconto em um pedido. Possui valor mínimo de pedido, validade, quantidade disponível e status ativo/inativo.

---

## D

**DoD (Definition of Done)**
Lista de critérios que uma funcionalidade deve satisfazer para ser considerada "pronta". Definida em cada spec e também globalmente em `docs/standards/definition-of-done.md`.

**DTO (Data Transfer Object)**
Objeto imutável (`readonly class`) que transporta dados entre camadas (ex: do Controller para o Service). Não contém lógica de negócio.

---

## E

**Estoque**
Quantidade disponível de uma variação de produto. Gerenciado pela tabela `estoque`. Cada registro de estoque está vinculado a uma variação específica.

---

## F

**Fallback de Asset**
Mecanismo que serve o asset do tema `default` quando o asset equivalente não existe no tema ativo. Implementado via helper `asset()`.

**Fallback de View**
Mecanismo que carrega a view do tema `default` quando a view equivalente não existe no tema ativo. Implementado via `FilesystemLoader` do Twig com múltiplos caminhos.

**Frete**
Custo de entrega calculado para um pedido. O cálculo é responsabilidade do módulo `Support`.

---

## G

**Guard (Auth Guard)**
Classe responsável por verificar se uma requisição tem sessão autenticada válida com o `role` correto. Chamado no início do `execute()` de controllers protegidos.

---

## M

**Módulo**
Unidade de organização do código que agrupa todas as classes de um contexto de negócio específico. Localizado em `app/Modules/{NomeDoModulo}/`. Contém Controllers, Services, Repositories, Models e DTOs.

---

## P

**Pedido (Order)**
Registro de uma compra finalizada. Criado a partir do Checkout. Possui status (`pendente`, `pago`, `cancelado`, etc.), total, referência ao usuário e itens.

**Produto**
Item do catálogo disponível para venda. Possui nome, preço, descrição, imagem e status. Um produto pode ter múltiplas variações.

---

## R

**Rate Limiting**
Controle de tentativas excessivas de login. Após 5 tentativas falhas em 15 minutos (por email + IP), o acesso é bloqueado por 30 minutos.

**Repository**
Classe PHP Single Action responsável por uma única operação de banco de dados (leitura ou escrita). Nunca contém lógica de negócio. Exemplo: `FindProductByIdRepository`.

**Role**
Perfil de acesso de um usuário. Valores possíveis: `admin` (operador do painel) e `user` (usuário final da aplicação).

---

## S

**Service**
Classe PHP Single Action que encapsula uma única regra de negócio. Orquestrada pelo Controller; interage com Repositories para persistência. Exemplo: `ProcessCheckoutService`.

**Sessão**
Mecanismo PHP (`$_SESSION`) usado para manter estado entre requisições. Usado para armazenar dados de autenticação (`auth.*`), carrinho e preferências do usuário.

**SKU (Stock Keeping Unit)**
Código único que identifica uma variação específica de produto. Composto pelos valores das variações (ex: `produto-1_cor-azul_tamanho-M`).

**Spec (Specification)**
Documento que descreve uma feature antes de sua implementação. Contém contexto, casos de uso, regras de negócio, contratos de interface e critérios de aceite. Armazenada em `docs/specs/`.

---

## T

**Template**
Tema visual da interface. Determina o conjunto de views (Twig) e assets (CSS/JS) usados para renderizar a aplicação. O template `default` é obrigatório e serve de fallback.

**Token de Reset**
String de 64 caracteres hexadecimais gerada com `bin2hex(random_bytes(32))`, usada para recuperação de senha. Tem validade de 1 hora e é de uso único.

---

## U

**Usuário (User)**
Pessoa com conta no sistema. Pode ser `admin` (operador) ou `user` (usuário final). Armazenado na tabela `users` com `role` ENUM.

---

## V

**Variação**
Atributo específico de um produto (ex: cor, tamanho). Cada combinação de atributos gera uma variação única com SKU próprio e estoque independente.

**View**
Arquivo de template Twig (`.twig`) que representa uma página ou componente da interface. Localizado em `resources/views/templates/{template}/`.
