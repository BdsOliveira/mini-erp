{% extends 'layouts/app.php' %}

{% block title %}Variações do produto{% endblock %}

{% block content %}
<h2>Adicione variações para o produto</h2>

<div class="bg-light mb-3 p-3 rounded border">
    <div class="row">
        <div class="mb-3 col-xs-12 col-md-2">
            <img src="{{ product.imagem }}" alt="{{ product.nome }}" class="mb-3 rounded w-100 img-fluid">
        </div>
        <div class="mb-3 col-md-10 fs-3">
            <div class="mb-3">
                <span>Item: <strong>{{ product.nome }}</strong></span>
            </div>
            <div class="mb-3">
                <span>Preço: R$ {{ product.preco }}</span>
            </div>
            <div class="mb-3 fs-5">
                <span>{{ product.descricao }}</span>
            </div>
        </div>
    </div>
</div>

<h2 class="mt-5">Adicione novas variações para o produto</h2>
<form method="post" action="/produtos/variacoes/cadastrar?produto_id={{ product.id }}">
    <div class="row">
        <div class="mb-3 col-xs-12 col-md-6">
            <label for="tipo" class="form-label">Tipo da variação do produto</label>
            <input type="text" class="form-control" placeholder="Ex: Tamanho, Cor, etc." id="tipo" name="tipo" required>
        </div>
        <div class="mb-3 col-xs-12 col-md-6">
            <label for="valor" class="form-label">Valores (separados por vírgula)</label>
            <input type="text" class="form-control" placeholder="Ex: Preto, Azul Marinho, Vermelho" id="valor"
                name="valor" required>
        </div>
    </div>
    <div class="row mt-3 px-2 d-flex justify-content-end align-items-center gap-2">
        <button type="submit" class="btn btn-primary block">Cadastrar Variação</button>
    </div>
</form>

<h2 class="mt-5">Variações do produto</h2>
{% for variant in variants %}
<div class="row bg-light my-2 pt-3 rounded border">
    <div class="col-md-8">
        <p>ID: {{ variant.id }} - {{ variant.tipo }}: {{ variant.valor }}</p>
        <p>SKU: {{ variant.sku }}</p>
    </div>
</div>
{% endfor %}
</div>

{% if not variants %}
<div class="container">
    <div class="alert alert-info rounded">
        <p>Este produto ainda não possui variações cadastradas.</p>
    </div>
</div>
{% endif %}

{% endblock %}