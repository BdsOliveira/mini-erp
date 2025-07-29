{% extends 'layouts/loja.php' %}

{% block title %}Carrinho - Lojão{% endblock %}

{% block content %}
{% if flash_message %}
<div class="my-3 alert alert-success" role="alert">
    {{ flash_message }}
</div>
{% endif %}

<div class="my-4">
    <h4>Carrinho de compras</h4>
</div>

<div class="container pb-5">
    {% for product in products %}
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">

                <div class="col-md-3 col-sm-4 col-12 mb-3 mb-md-0">
                    <img src="{{ product.imagem }}" class="img-fluid rounded" alt="{{ product.nome }}"
                        style="max-height: 150px; object-fit: cover; width: 100%;">
                </div>

                <div class="col-md-6 col-sm-8 col-12 mb-3 mb-md-0">
                    <h5 class="card-title mb-2">{{ product.nome }}</h5>
                    <p class="card-text text-muted small mb-2">{{ product.descricao }}</p>

                    {% if product.preco %}
                    <div class="mb-2">
                        <span class="h6 text-success">R$ {{ "%.2f"|format(product.preco) }}</span>
                    </div>
                    {% endif %}
                </div>

                <div class="col-md-3 col-12 text-md-end">
                    <form action="/carrinho/delete-item" method="post"
                        onsubmit="return confirm('Deseja realmente remover este item do carrinho?')">
                        <input type="hidden" name="productId" id="productId" value="{{ product.id }}">
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fa fa-trash"> </i> Remover
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {% endfor %}
    <div class="col-12">
        {% if subtotal > 0 %}
        <div class="card shadow-sm position-sticky" style="top: 20px;">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="bi bi-receipt"></i> Resumo do Pedido
                </h5>
            </div>
            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span>Itens:</span>
                    <span>{{ total_items }}</span>
                </div>

                {% if subtotal > 0 %}
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>R$ {{ "%.2f"|format(subtotal) }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Frete:</span>
                    {% if frete > 0 %}
                    <span>R$ {{ "%.2f"|format(frete) }}</span>
                    {% else %}
                    <span class="text-success">Grátis</span>
                    {% endif %}
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Cupom aplicado:</span>
                    {% if cupom_valor != 0 %}
                    <span>R$ {{ "%.2f"|format(cupom_valor) }}</span>
                    {% else %}
                    <span class="">0.00</span>
                    {% endif %}
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong class="text-success">R$ {{ "%.2f"|format(total) }}</strong>
                </div>
                {% endif %}

                <div class="mb-3">
                    <form action="/carrinho/validar-cupom" method="post">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control text-uppercase" id="cupom" name="cupom"
                                value="{{ cupom }}" placeholder="Cupom de desconto">
                            <button type="submit" class="btn btn-outline-secondary">
                                Aplicar
                            </button>
                        </div>
                    </form>
                </div>

                <div class="d-grid gap-2">
                    <a href="/checkout" class="btn btn-success">
                        <i class="bi bi-credit-card"></i> Finalizar Compra
                    </a>
                </div>
            </div>
        </div>
        {% else %}
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <div class="card shadow-sm">
                    <div class="card-body py-5">
                        <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
                        <h4 class="text-muted mb-3">Seu carrinho está vazio</h4>
                        <p class="text-muted mb-4">
                            Adicione alguns produtos para começar suas compras!
                        </p>
                        <a href="/" class="btn btn-primary btn-lg">
                            <i class="bi bi-shop"></i> Ir às Compras
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {% endif %}
    </div>
</div>

{% endblock %}