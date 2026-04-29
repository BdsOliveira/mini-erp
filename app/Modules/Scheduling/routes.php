<?php

use App\Modules\Scheduling\Controllers\SchedulingController;

return [
    "GET" => [
        "/agendamento" => [SchedulingController::class, "index"],
    ],
    "POST" => []
];
