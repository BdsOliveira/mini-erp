<?php

declare(strict_types=1);

namespace Framework\View\Traits;

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
        $loader = new FilesystemLoader(__DIR__ . '/../../../resources/views/');

        $this->twig = new Environment($loader);

        $lexer = new Lexer(
            $this->twig,
            $this->functions(),
        );

        $this->twig->setLexer($lexer);
    }

    public function view(string $template, array $data = []): void
    {
        echo $this->twig->render($template, $data);
    }

    public function functions(): array
    {
        if (!function_exists('dd')) {
            require_once __DIR__ . '/../functions/dd.php';
        }
        return [
            $this->twig->addFunction(new TwigFunction('dd', fn(array $data) => dd($data))),
        ];
    }
}