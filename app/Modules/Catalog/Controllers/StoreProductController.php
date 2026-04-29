<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Catalog\DTOs\ProductDTO;
use App\Modules\Catalog\Repositories\Products\Save;
use Framework\Http\Request;

class StoreProductController extends BaseController
{
    private $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new Save();
    }

    public function execute(): void
    {
        $product = new ProductDTO([...Request::all(), 'imagem' => Request::image('imagem')]);

        $this->repository->execute(product: $product->fromArray());

        $this->redirect('/produtos');
    }
}
