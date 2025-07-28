{% extends 'layouts/loja.php' %}

{% block title %}Lojão{% endblock %}

{% block content %}
{% if flash_message %}
<div class="my-3 alert alert-success" role="alert">
    {{ flash_message }}
</div>
{% endif %}

<div class="my-4">
    <h1>Sem bem vindo ao Super Lojão</h1>
</div>

<div class="my-4">
    <h4>Conheça nossos produtos</h4>
</div>

<div class="container">
    <div class="row">
        {% for product in products %}
        <div class="col col-12 col-md-6 col-lg-4 col-xl-3 my-2">
            <div class="card">
                <img src="{{ product.imagem }}" class="card-img-top" alt="{{ product.nome }}">
                <div class="card-body">
                    <h5 class="card-title">{{ product.nome }}</h5>
                    <p class="card-text">{{ product.descricao }}</p>
                    <form action="/carrinho" method="post">
                        <input type="hidden" name="id" name="id" value="{{ product.id }}">
                        <button type="submit" class="btn btn-primary">Adicionar ao carrinho</button>
                    </form>
                </div>
            </div>
        </div>
        {% endfor %}
    </div>
</div>
{% endblock %}