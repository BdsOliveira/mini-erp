<?php

declare(strict_types=1);

namespace Framework\View\functions;

/** @param array<mixed> $data */
function dd(array $data): void
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}