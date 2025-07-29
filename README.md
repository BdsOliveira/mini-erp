# Mini ERP

###  Pré-requisitos
1. PHP 8.4+
2. Composer 2.5+
3. Mysql 8+

## Como executar o projeto?
Copie o arquivo que devem ser inseridas as variáveis de ambiente e adicione as suas configurações de banco de dados e credenciais do seu provedor de envio de email:
```bash copy
cp .env.example .env
```

O esquema do banco de dados você pode encontrar em `database/schema.sql`, basta importar para o seu banco de dados.

Instale as depedências do projeto com o composer:
```bash copy
composer install
```

Execute o projeto com o script serve configurado no composer.json:
```bash copy
composer serve
```

Pronto!

Para acessar a parte administrativa, acesse `http://localhost:8000/admin`

Para acessar a loja virtual, acesse `http://localhost:8000/`

## Não quer ficar cadastrando produtos?
- Utilize o arquivo `database/populate.sql` e importe alguns produtos e cupons para o seu banco de dados recém criado.
