<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Catalog\DTOs\ProductDTO;
use App\Modules\Catalog\Repositories\Products\UpdateProductRepository;
use Framework\Http\Request;

class UpdateProductController extends BaseController
{
    private $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new UpdateProductRepository();
    }

    public function execute(): void
    {
        $product = (new ProductDTO([...Request::all(), 'imagem' => Request::image('imagem')]))->fromArray();
        $this->repository->execute(productId: (int) Request::get('id'), product: $product);
        $this->redirect('/produtos');
    }
}
