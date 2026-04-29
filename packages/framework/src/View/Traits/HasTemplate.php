<?php

declare(strict_types=1);

namespace Framework\View\Traits;

use Framework\Utils\Session;
use Twig\Environment;
use Twig\Lexer;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use function Framework\View\functions\dd;

trait HasTemplate
{
    private Environment $twig;

    public function __construct()
    {
        $root = dirname(getcwd());
        $loader = new FilesystemLoader($root . '/resources/views/');

        $this->twig = new Environment($loader);

        $this->registerFunctions();

        $lexer = new Lexer($this->twig);

        $this->twig->setLexer($lexer);
    }

    public function render(string $template, array $data = []): void
    {
        echo $this->twig->render($template, $data);
    }

    private function registerFunctions(): void
    {
        if (!function_exists('dd')) {
            require_once __DIR__ . '/../functions/dd.php';
        }
        $this->twig->addFunction(new TwigFunction('dd', fn(array $data) => dd($data)));
        $this->twig->addFunction(new TwigFunction('getCartItemsQtd', fn() => $this->getCartItemsQtd()));
    }

    public function getCartItemsQtd(): int
    {
        return Session::count(key: 'cart');
    }
}