<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Catalog\Repositories\Products\GetPaginatedProductsRepository;

class ListProductsController extends BaseController
{
    private $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new GetPaginatedProductsRepository();
    }

    public function execute(): void
    {
        $products = $this->repository->execute();
        $this->render('produtos/index.php', ['products' => $products]);
    }
}
