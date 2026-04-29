<?php

declare(strict_types=1);

use Dotenv\Dotenv;

beforeEach(function () {
    $this->envDir = sys_get_temp_dir() . '/env_test_' . uniqid();
    mkdir($this->envDir);
});

afterEach(function () {
    $envFile = $this->envDir . '/.env';
    if (file_exists($envFile)) {
        unlink($envFile);
    }
    rmdir($this->envDir);
});

test('carrega variáveis de ambiente de um arquivo .env', function () {
    file_put_contents($this->envDir . '/.env', "APP_NAME=MiniERP\nAPP_ENV=testing\n");

    $dotenv = Dotenv::createImmutable($this->envDir);
    $dotenv->load();

    expect($_ENV['APP_NAME'])->toBe('MiniERP');
    expect($_ENV['APP_ENV'])->toBe('testing');
});

test('variáveis carregadas ficam disponíveis via $_ENV', function () {
    file_put_contents($this->envDir . '/.env', "DB_CONNECTION=sqlite\nDB_PORT=3306\n");

    $dotenv = Dotenv::createImmutable($this->envDir);
    $dotenv->load();

    expect($_ENV['DB_CONNECTION'])->toBe('sqlite');
    expect($_ENV['DB_PORT'])->toBe('3306');
});

test('variável com espaços no valor é carregada corretamente', function () {
    file_put_contents($this->envDir . '/.env', "APP_DESC=\"Framework PHP Minimalista\"\n");

    $dotenv = Dotenv::createImmutable($this->envDir);
    $dotenv->load();

    expect($_ENV['APP_DESC'])->toBe('Framework PHP Minimalista');
});

test('variável ausente no .env não está em $_ENV', function () {
    file_put_contents($this->envDir . '/.env', "OUTRA_VAR=valor\n");

    $dotenv = Dotenv::createImmutable($this->envDir);
    $dotenv->load();

    expect(isset($_ENV['VAR_INEXISTENTE']))->toBeFalse();
});
