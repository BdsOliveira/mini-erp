{% extends 'layouts/loja.php' %}

{% block title %}Checkout - Lojão{% endblock %}

{% block content %}
{% if flash_message %}
<div class="my-3 alert alert-success" role="alert">
    {{ flash_message }}
</div>
{% endif %}

<div class="my-4">
    <h4>Checkout</h4>
</div>

<div class="container pb-5">
    <form id="form" action="/checkout" method="post">
        <div class="mb-4">
            <h5 class="text-secondary mb-3">
                <i class="fas fa-user me-2"></i>
                Informações Pessoais
            </h5>

            <div class="mb-3">
                <label for="nome" class="form-label">Nome Completo *</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail *</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
        </div>

        <div class="mb-4">
            <h5 class="text-secondary mb-3">
                <i class="fas fa-map-marker-alt me-2"></i>
                Endereço de Entrega
            </h5>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="cep" class="form-label">CEP *</label>
                    <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cidade" class="form-label">Cidade *</label>
                    <input type="text" class="form-control" id="cidade" name="cidade" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="estado" class="form-label">Estado *</label>
                    <select class="form-select" id="estado" name="estado" required>
                        <option value="">Selecione</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="endereco" class="form-label">Endereço *</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Rua, Avenida..."
                        required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="numero" class="form-label">Número *</label>
                    <input type="text" class="form-control" id="numero" name="numero" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="complemento" class="form-label">Complemento</label>
                    <input type="text" class="form-control" id="complemento" name="complemento"
                        placeholder="Apto, Casa, Bloco...">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="bairro" class="form-label">Bairro *</label>
                    <input type="text" class="form-control" id="bairro" name="bairro" required>
                </div>
            </div>

        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="/carrinho" class="btn btn-outline-secondary me-md-2">
                <i class="fas fa-arrow-left me-2"></i>
                Voltar
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-credit-card me-2"></i>
                Finalizar Pedido
            </button>
        </div>
    </form>
</div>
<script src="/js/checkout.js"></script>

{% endblock %}
