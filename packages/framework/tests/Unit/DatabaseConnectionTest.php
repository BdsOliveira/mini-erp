<?php

declare(strict_types=1);

use Framework\Database\Connection;

/**
 * Injeta um PDO via Reflection na propriedade estática privada $instance
 * e retorna a closure para resetar ao valor original ao final do teste.
 */
function injectPdo(PDO $pdo): Closure
{
    $ref = new ReflectionProperty(Connection::class, 'instance');
    $ref->setAccessible(true);
    $original = $ref->getValue();
    $ref->setValue(null, $pdo);

    return function () use ($ref, $original) {
        $ref->setValue(null, $original);
    };
}

function makeSqlitePdo(): PDO
{
    return new PDO('sqlite::memory:', options: [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

// ---------------------------------------------------------------------------

test('getInstance retorna uma instância de PDO', function () {
    $pdo = makeSqlitePdo();
    $reset = injectPdo($pdo);

    expect(Connection::getInstance())->toBeInstanceOf(PDO::class);

    $reset();
});

test('getInstance é um singleton — retorna sempre o mesmo objeto', function () {
    $pdo = makeSqlitePdo();
    $reset = injectPdo($pdo);

    $a = Connection::getInstance();
    $b = Connection::getInstance();

    expect($a)->toBe($b);

    $reset();
});

test('conexão executa query e retorna resultados', function () {
    $pdo = makeSqlitePdo();
    $reset = injectPdo($pdo);

    $conn = Connection::getInstance();
    $conn->exec('CREATE TABLE usuarios (id INTEGER PRIMARY KEY, nome TEXT)');
    $conn->exec("INSERT INTO usuarios (nome) VALUES ('Bruno')");

    $stmt = $conn->query('SELECT * FROM usuarios');
    $rows = $stmt->fetchAll();

    expect($rows)->toHaveCount(1);
    expect($rows[0]['nome'])->toBe('Bruno');

    $reset();
});

test('conexão suporta transações — commit persiste dados', function () {
    $pdo = makeSqlitePdo();
    $reset = injectPdo($pdo);

    $conn = Connection::getInstance();
    $conn->exec('CREATE TABLE produtos (id INTEGER PRIMARY KEY, nome TEXT)');

    $conn->beginTransaction();
    $conn->exec("INSERT INTO produtos (nome) VALUES ('Produto A')");
    $conn->commit();

    $count = $conn->query('SELECT COUNT(*) as total FROM produtos')->fetch()['total'];
    expect((int) $count)->toBe(1);

    $reset();
});

test('conexão suporta transações — rollback descarta dados', function () {
    $pdo = makeSqlitePdo();
    $reset = injectPdo($pdo);

    $conn = Connection::getInstance();
    $conn->exec('CREATE TABLE logs (id INTEGER PRIMARY KEY, msg TEXT)');

    $conn->beginTransaction();
    $conn->exec("INSERT INTO logs (msg) VALUES ('entrada temporaria')");
    $conn->rollBack();

    $count = $conn->query('SELECT COUNT(*) as total FROM logs')->fetch()['total'];
    expect((int) $count)->toBe(0);

    $reset();
});
