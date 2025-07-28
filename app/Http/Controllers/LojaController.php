<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\ProductsRepository;
use Framework\Utils\Session;

class LojaController extends BaseController
{
    private $productsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productsRepository = new ProductsRepository();
    }
    public function index()
    {
        return $this->render('loja/index.php', [
            'products' => $this->productsRepository->getPaginated(limit: 30),
            'flash_message' => Session::getFlash(),
        ]);
    }
}
