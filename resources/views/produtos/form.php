{% extends 'layouts/app.php' %}

{% block title %}Criar Produto{% endblock %}

{% block content %}
<h1>Criar Produto</h1>

<form method="post" action="/produtos" enctype="multipart/form-data">
    <div class="row">
        <div class="mb-3 col-xs-12 col-md-6">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="mb-3 col-xs-12 col-md-6">
            <label for="preco" class="form-label">Preço do Produto</label>
            <input type="number" step="0.01" class="form-control" id="preco" name="preco" required>
        </div>
    </div>
    <div class="row">
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição do Produto</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="mb-3">
            <label for="imagem" class="form-label">Imagem do Produto</label>
            <input type="file" class="form-control" id="imagem" name="imagem">
        </div>
    </div>
    <div class="row mt-3 px-2 d-flex justify-content-end align-items-center gap-2">
        <button type="submit" class="btn btn-primary block">Cadastrar produto</button>
    </div>
</form>

{% endblock %}