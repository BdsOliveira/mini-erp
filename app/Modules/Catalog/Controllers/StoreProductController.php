<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Catalog\DTOs\ProductDTO;
use App\Modules\Catalog\Repositories\ProductsRepository;
use Framework\Http\Request;

class StoreProductController extends BaseController
{
    private $productsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productsRepository = new ProductsRepository();
    }

    public function execute(): void
    {
        $product = new ProductDTO([...Request::all(), 'imagem' => Request::image('imagem')]);

        $this->productsRepository->save(product: $product->fromArray());

        $this->redirect('/produtos');
    }
}
