<!DOCTYPE html>
<html lang="pr_BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{% block title %}{% endblock %}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom"> <a href="/admin"
                class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none"> <svg
                    class="bi me-2" width="40" height="32" aria-hidden="true">
                    <use xlink:href="#bootstrap"></use>
                </svg> <span class="fs-4">Mini ERP</span> </a>
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="/produtos" class="nav-link">Produtos</a></li>
                <li class="nav-item"><a href="/pedidos" class="nav-link">Pedidos</a></li>
                <li class="nav-item"><a href="/" class="btn btn-primary">Visitar Loja</a></li>
            </ul>
        </header>
    </div>
    <div class="b-example-divider"></div>
    <div class="container">
        {% block content %}{% endblock %}
    </div>
    <div class="container footer">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <div class="col-md-4 d-flex align-items-center">
                <a href="/admin" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1"
                    aria-label="Bootstrap">
                    <svg class="bi" width="30" height="24" aria-hidden="true">
                        <use xlink:href="#bootstrap"></use>
                    </svg>
                </a>
                <span class="mb-3 mb-md-0 text-body-secondary">&copy;
                    <script>document.write(new Date().getFullYear());</script> MiniERP, Inc
                </span>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
</body>

</html>