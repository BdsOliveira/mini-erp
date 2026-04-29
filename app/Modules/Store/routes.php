<?php

use App\Modules\Store\Controllers\ListStoreController;

return [
    "GET" => [
        '' => [ListStoreController::class, "execute"],
    ],
    "POST" => []
];
