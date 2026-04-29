<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Catalog\Repositories\Products\GetProductByIdRepository;
use App\Modules\Catalog\Repositories\Variants\GetVariantByIdRepository;
use App\Modules\Catalog\Repositories\Stock\GetStockQuantityRepository;
use Framework\Http\Request;

class EditProductVariantController extends BaseController
{
    private $productRepository;
    private $variantRepository;
    private $stockRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productRepository = new GetProductByIdRepository();
        $this->variantRepository = new GetVariantByIdRepository();
        $this->stockRepository = new GetStockQuantityRepository();
    }

    public function execute(): void
    {
        $productId = (int) Request::get('produto_id');
        $variantId = (int) Request::get('variant_id');

        $product = $this->productRepository->execute(id: $productId);
        if (!$product) {
            $this->redirect('/not-found');
        }

        $variant = $this->variantRepository->execute(productId: $productId, variantId: $variantId);
        if (!$variant) {
            $this->redirect('/not-found');
        }

        $this->render('produtos/edit-variant.php', [
            'product' => $product,
            'variant' => $variant,
            'estoque' => $this->stockRepository->execute(variantId: $variantId),
        ]);
    }
}
