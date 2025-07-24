{% extends 'layouts/app.php' %}

{% block title %}Produtos{% endblock %}

{% block content %}
<div class="d-flex">
    <h1>Produtos</h1>
    <div class="" style="margin-left: auto">
        <a href="/produtos/criar" class="btn btn-primary">Criar produto</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th width="15%">ID</th>
            <th width="35%">Nome</th>
            <th width="25%">Preço</th>
            <th width="25%">Ações</th>
        </tr>
    </thead>
    <tbody>
        {% for product in products %}
        <tr>
            <td>{{ product.id }}</td>
            <td>{{ product.nome }}</td>
            <td>{{ product.preco }}</td>
            <td>
                <a href="/produtos/variacoes?produto_id={{ product.id }}" class="btn btn-warning">
                    Adicionar variações
                </a>
            </td>
        </tr>
        {% endfor %}
    </tbody>
</table>
{% endblock %}