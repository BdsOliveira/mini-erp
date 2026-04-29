<?php

declare(strict_types=1);

use Framework\Http\Request;

beforeEach(function () {
    $_SERVER = [];
    $_REQUEST = [];
});

test('method retorna o método HTTP da requisição', function () {
    $_SERVER['REQUEST_METHOD'] = 'POST';
    expect(Request::method())->toBe('POST');
});

test('method retorna GET como padrão quando não definido', function () {
    expect(Request::method())->toBe('GET');
});

test('path extrai o caminho sem query string', function () {
    $_SERVER['REQUEST_URI'] = '/usuarios/perfil?tab=info';
    expect(Request::path())->toBe('/usuarios/perfil');
});

test('path remove a barra final', function () {
    $_SERVER['REQUEST_URI'] = '/sobre/';
    expect(Request::path())->toBe('/sobre');
});

test('path retorna raiz quando URI é apenas barra', function () {
    $_SERVER['REQUEST_URI'] = '/';
    expect(Request::path())->toBe('');
});

test('query extrai a query string da URI', function () {
    $_SERVER['REQUEST_URI'] = '/busca?q=framework&page=2';
    expect(Request::query())->toBe('q=framework&page=2');
});

test('query retorna string vazia quando não há query', function () {
    $_SERVER['REQUEST_URI'] = '/sem-query';
    expect(Request::query())->toBe('');
});

test('get retorna o valor sanitizado de um campo da requisição', function () {
    $_REQUEST['nome'] = 'Bruno';
    expect(Request::get('nome'))->toBe('Bruno');
});

test('get sanitiza caracteres HTML perigosos', function () {
    $_REQUEST['campo'] = '<script>alert("xss")</script>';
    expect(Request::get('campo'))->toBe('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;');
});

test('get retorna string vazia para campo inexistente', function () {
    expect(Request::get('inexistente'))->toBe('');
});

test('all retorna todos os campos da requisição sanitizados', function () {
    $_REQUEST['a'] = 'valor1';
    $_REQUEST['b'] = '<b>bold</b>';
    $all = Request::all();
    expect($all['a'])->toBe('valor1');
    expect($all['b'])->toBe('&lt;b&gt;bold&lt;/b&gt;');
});
