<?php

declare(strict_types=1);

use Framework\Utils\Session;

beforeEach(function () {
    $_SESSION = [];
});

test('set e get armazenam e recuperam um valor', function () {
    Session::set('nome', 'Bruno');
    expect(Session::get('nome'))->toBe('Bruno');
});

test('get retorna null para chave inexistente', function () {
    expect(Session::get('inexistente'))->toBeNull();
});

test('has retorna true para chave existente e false para ausente', function () {
    Session::set('presente', 'sim');
    expect(Session::has('presente'))->toBeTrue();
    expect(Session::has('ausente'))->toBeFalse();
});

test('remove exclui a chave da sessão', function () {
    Session::set('remover', 'valor');
    Session::remove('remover');
    expect(Session::has('remover'))->toBeFalse();
});

test('push adiciona valores ao array da chave', function () {
    Session::push('lista', 'a');
    Session::push('lista', 'b');
    expect(Session::get('lista'))->toBe(['a', 'b']);
});

test('push não duplica valores já existentes', function () {
    Session::push('lista', 'x');
    Session::push('lista', 'x');
    expect(Session::count('lista'))->toBe(1);
});

test('count retorna a quantidade de itens na chave', function () {
    Session::push('itens', 1);
    Session::push('itens', 2);
    Session::push('itens', 3);
    expect(Session::count('itens'))->toBe(3);
});

test('count retorna zero para chave inexistente', function () {
    expect(Session::count('vazia'))->toBe(0);
});

test('clear esvazia toda a sessão', function () {
    Session::set('a', 1);
    Session::set('b', 2);
    Session::clear();
    expect($_SESSION)->toBe([]);
});

test('all retorna todos os dados da sessão', function () {
    Session::set('x', 10);
    Session::set('y', 20);
    expect(Session::all())->toBe(['x' => 10, 'y' => 20]);
});

test('flash armazena e getFlash recupera e remove o valor', function () {
    Session::flash('mensagem de sucesso');
    expect(Session::getFlash())->toBe('mensagem de sucesso');
    expect(Session::has('flash'))->toBeFalse();
});
