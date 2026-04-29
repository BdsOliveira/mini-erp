<?php

use App\Modules\Core\Controllers\ErroController;
use App\Modules\Core\Controllers\HomeController;

return [
    "GET" => [
        "/admin" => [HomeController::class, "index"],
        "/not-found" => [ErroController::class, "notFound"],
    ],
    "POST" => []
];
