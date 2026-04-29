<?php

declare(strict_types=1);

namespace App\Modules\Core\Controllers;

use App\Modules\Core\Base\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
        return $this->render('index.php');
    }
}