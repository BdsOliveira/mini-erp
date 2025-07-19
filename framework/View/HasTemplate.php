<?php

declare(strict_types=1);

namespace Framework\View;

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
        $loader = new FilesystemLoader('/../resources/views');

        $this->twig = new Environment($loader);

        $lexer = new Lexer(
            $this->twig,
            [
                $this->functions(),
            ]
        );

        $this->twig->setLexer($lexer);
    }

    public function view(string $template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }

    public function functions(): array
    {
        return [
            $this->twig->addFunction(new TwigFunction('dd', fn(array $data) => dd($data))),
        ];
    }
}