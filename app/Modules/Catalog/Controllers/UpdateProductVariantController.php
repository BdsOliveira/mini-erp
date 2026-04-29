<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Catalog\Repositories\Products\GetByIdRepository;
use App\Modules\Catalog\Repositories\Variants\GetVariantByIdRepository;
use App\Modules\Catalog\Repositories\Variants\UpdateVariantRepository;
use App\Modules\Catalog\Repositories\Stock\UpdateStockRepository;
use Framework\Http\Request;

class UpdateProductVariantController extends BaseController
{
    private $productRepository;
    private $variantRepository;
    private $updateVariantRepository;
    private $updateStockRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productRepository = new GetByIdRepository();
        $this->variantRepository = new GetVariantByIdRepository();
        $this->updateVariantRepository = new UpdateVariantRepository();
        $this->updateStockRepository = new UpdateStockRepository();
    }

    public function execute(): void
    {
        $productId = (int) Request::get('produto_id');
        $variantId = (int) Request::get('variant_id');
        $stock = (int) Request::get('estoque');

        if (!$this->productRepository->execute(id: $productId) ||
            !$this->variantRepository->execute(productId: $productId, variantId: $variantId)) {
            $this->redirect('/not-found');
        }

        $this->updateVariantRepository->execute(
            productId: $productId,
            variantId: $variantId,
            type: (string) Request::get('tipo'),
            value: (string) Request::get('valor')
        );

        $this->updateStockRepository->execute(productId: $productId, variantId: $variantId, stock: $stock);

        $this->redirect("/produtos/variacoes?produto_id=$productId");
    }
}
