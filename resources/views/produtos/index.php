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
            <th>ID</th>
            <th>Nome</th>
            <th>Preço</th>
        </tr>
    </thead>
    <tbody>
        {% for product in products %}
        <tr>
            <td>{{ product.id }}</td>
            <td>{{ product.nome }}</td>
            <td>{{ product.preco }}</td>
        </tr>
        {% endfor %}
    </tbody>
    {% endblock %}