{% extends 'layouts/app.php' %}

{% block title %}Produtos{% endblock %}

{% block content %}

<div class="container-fluid vh-100">
    <div class="d-flex flex-column justify-content-center align-items-center h-50">
        <div class="alert alert-danger p-4 rounded shadow text-center">
            <h3 class="mb-0">404 - Não encontrado</h3>
            <p class="mb-0">O recurso que você está procurando não foi encontrado!</p>
        </div>
        <div class="block mt-4">
            <a href="/" class="btn btn-primary">Ir para início</a>
        </div>
    </div>
</div>

{% endblock %}