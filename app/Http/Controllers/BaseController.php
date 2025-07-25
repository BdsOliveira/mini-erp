<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Framework\View\Traits\HasTemplate;

abstract class BaseController
{
    use HasTemplate;

    public function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}