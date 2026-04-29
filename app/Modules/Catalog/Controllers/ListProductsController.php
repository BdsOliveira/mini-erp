<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Catalog\Repositories\ProductsRepository;

class ListProductsController extends BaseController
{
    private $productsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productsRepository = new ProductsRepository();
    }

    public function execute(): void
    {
        $products = $this->productsRepository->getPaginated();
        $this->render('produtos/index.php', ['products' => $products]);
    }
}
