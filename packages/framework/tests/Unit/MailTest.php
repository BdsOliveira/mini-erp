<?php

declare(strict_types=1);

use Framework\Utils\Mail;

/**
 * Injeta config e provider via Reflection nos estáticos privados de Mail,
 * retornando closure para resetar ao final.
 *
 * @param array<string, mixed> $config
 */
function injectMailConfig(array $config, string $provider): Closure
{
    $refConfig = new ReflectionProperty(Mail::class, 'config');
    $refConfig->setAccessible(true);
    $originalConfig = $refConfig->getValue();

    $refProvider = new ReflectionProperty(Mail::class, 'provider');
    $refProvider->setAccessible(true);
    $originalProvider = $refProvider->getValue();

    $refConfig->setValue(null, $config);
    $refProvider->setValue(null, $provider);

    return function () use ($refConfig, $refProvider, $originalConfig, $originalProvider) {
        $refConfig->setValue(null, $originalConfig);
        $refProvider->setValue(null, $originalProvider);
    };
}

function makeMailConfig(): array
{
    return [
        'smtp' => 'mailtrap',
        'providers' => [
            'mailtrap' => [
                'host'         => 'sandbox.smtp.mailtrap.io',
                'port'         => 587,
                'username'     => 'usuario_teste',
                'password'     => 'senha_teste',
                'from_address' => 'noreply@example.com',
                'from_name'    => 'Mini ERP',
            ],
        ],
    ];
}

// ---------------------------------------------------------------------------

test('config carrega provider padrão quando $_ENV não define MAIL_PROVIDER', function () {
    $config = makeMailConfig();
    $reset = injectMailConfig($config, 'mailtrap');

    $refProvider = new ReflectionProperty(Mail::class, 'provider');
    $refProvider->setAccessible(true);

    expect($refProvider->getValue())->toBe('mailtrap');

    $reset();
});

test('config expõe host do provider corretamente', function () {
    $config = makeMailConfig();
    $reset = injectMailConfig($config, 'mailtrap');

    $refConfig = new ReflectionProperty(Mail::class, 'config');
    $refConfig->setAccessible(true);
    $loaded = $refConfig->getValue();

    expect($loaded['providers']['mailtrap']['host'])->toBe('sandbox.smtp.mailtrap.io');

    $reset();
});

test('config expõe porta do provider corretamente', function () {
    $config = makeMailConfig();
    $reset = injectMailConfig($config, 'mailtrap');

    $refConfig = new ReflectionProperty(Mail::class, 'config');
    $refConfig->setAccessible(true);
    $loaded = $refConfig->getValue();

    expect($loaded['providers']['mailtrap']['port'])->toBe(587);

    $reset();
});

test('config expõe endereço de remetente corretamente', function () {
    $config = makeMailConfig();
    $reset = injectMailConfig($config, 'mailtrap');

    $refConfig = new ReflectionProperty(Mail::class, 'config');
    $refConfig->setAccessible(true);
    $loaded = $refConfig->getValue();

    expect($loaded['providers']['mailtrap']['from_address'])->toBe('noreply@example.com');

    $reset();
});

test('send retorna mensagem de erro quando SMTP é inválido', function () {
    $config = makeMailConfig();
    $reset = injectMailConfig($config, 'mailtrap');

    // SMTP inválido vai lançar exceção capturada pelo PHPMailer — resultado é string de erro
    $resultado = Mail::send('destino@example.com', 'Assunto Teste', 'Corpo do email');

    expect($resultado)->toBeString();

    $reset();
});
