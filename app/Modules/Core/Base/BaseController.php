<?php

declare(strict_types=1);

namespace App\Modules\Core\Base;

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