{% extends 'layouts/app.php' %}

{% block title %}Variações do produto{% endblock %}

{% block content %}
<div class="mb-3 d-flex justify-content-between align-items-center">
    <h2>Editar variação do produto</h2>
    <a href="/produtos/variacoes?produto_id={{ product.id }}" class="btn btn-primary">Voltar</a>
</div>

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

<h2 class="mt-5">Variações do produto</h2>
<form method="post" action="/produtos/variacoes/update?produto_id={{ product.id }}&variant_id={{ variant.id }}">
    <div class="row">
        <div class="mb-3 col-xs-12 col-md-6">
            <label for="tipo" class="form-label">Tipo da variação do produto</label>
            <input type="text" class="form-control bg-light" placeholder="Ex: Tamanho, Cor, etc." id="tipo" name="tipo"
                value="{{ variant.tipo }}" readonly required>
        </div>
        <div class="mb-3 col-xs-12 col-md-6">
            <label for="valor" class="form-label">Variação</label>
            <input type="text" class="form-control" placeholder="Ex: Preto, Azul Marinho, Vermelho" id="valor"
                name="valor" value="{{ variant.valor }}" required>
        </div>
    </div>
    <div class="row">
        <div class="mb-3 col-xs-12 col-md-6">
            <label for="sku" class="form-label">SKU</label>
            <input type="text" class="form-control bg-light" id="sku" name="sku"
                value="{{ variant.sku }}" readonly required>
        </div>
        <div class="mb-3 col-xs-12 col-md-6">
            <label for="estoque" class="form-label">Quantidade em estoque</label>
            <input type="number" step="1" class="form-control" id="estoque"
                name="estoque" value="{{ estoque }}" required>
        </div>
    </div>
    <div class="row mt-3 px-2 d-flex justify-content-end align-items-center gap-2">
        <button type="submit" class="btn btn-primary block">Salvar</button>
    </div>
</form>

{% if not variant %}
<div class="container">
    <div class="alert alert-info rounded">
        <p>Este produto ainda não possui variações cadastradas.</p>
    </div>
</div>
{% endif %}

{% endblock %}