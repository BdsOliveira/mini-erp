<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Catalog\Repositories\Products\GetByIdRepository;
use App\Modules\Catalog\Repositories\Variants\GetProductVariantsRepository;
use Framework\Http\Request;

class ListProductVariantsController extends BaseController
{
    private $productRepository;
    private $variantsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productRepository = new GetByIdRepository();
        $this->variantsRepository = new GetProductVariantsRepository();
    }

    public function execute(): void
    {
        $productId = (int) Request::get('produto_id');

        $product = $this->productRepository->execute(id: $productId);
        if (!$product) {
            $this->redirect('/not-found');
        }

        $variants = $this->variantsRepository->execute(productId: $productId);

        $this->render('produtos/variants.php', [
            'product' => $product,
            'variants' => $variants,
        ]);
    }
}
