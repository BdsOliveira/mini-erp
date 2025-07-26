{% extends 'layouts/app.php' %}

{% block title %}Criar Produto{% endblock %}

{% block content %}
<div class="d-flex justify-content-between align-items-center">
    <h1> {{ product.id ? 'Editar' : 'Criar' }} Produto</h1>
    <a href="/produtos" class="btn btn-primary">Voltar</a>
</div>

{{ product }}

<form method="post" action="{{ product.id ? '/produtos/update' : 'produtos' }}" enctype="multipart/form-data">
    <div class="row">
        <div class="mb-3 col-xs-12 col-md-6">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ product.nome }}" required>
        </div>
        <div class="mb-3 col-xs-12 col-md-6">
            <label for="preco" class="form-label">Preço do Produto</label>
            <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="{{ product.preco }}"
                required>
        </div>
    </div>
    <div class="row">
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição do Produto</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ product.descricao }}</textarea>
        </div>
    </div>
    <div class="row d-flex">
        {% if (product.id) %}
        <input type="hidden" name="id" id="id" value="{{ product.id }}">
        <div class="mb-3 col-md-2">
            <img src="{{ product.imagem }}" alt="{{product.nome}}" class="mb-3 rounded w-75 img-fluid">
        </div>
        <div class="mb-3 col-md-10">
            <label for="imagem" class="form-label">Substituir imagem do produto</label>
            <input type="file" class="form-control" id="imagem" name="imagem" value="{{ product.imagem }}"
                accept="image/*">
        </div>
        {% else %}
        <div class="mb-3 col-12">
            <label for="imagem" class="form-label">Imagem do Produto</label>
            <input type="file" class="form-control" id="imagem" name="imagem" value="{{ product.imagem }}"
                accept="image/*">
        </div>
        {% endif %}
    </div>
    <div class="row mt-3 px-2 d-flex justify-content-end align-items-center gap-2">
        <button type="submit" class="btn btn-primary block">Salvar produto</button>
    </div>
</form>

{% endblock %}