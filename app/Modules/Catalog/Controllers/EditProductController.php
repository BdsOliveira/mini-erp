<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Catalog\Repositories\Products\GetProductByIdRepository;
use Framework\Http\Request;

class EditProductController extends BaseController
{
    private $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new GetProductByIdRepository();
    }

    public function execute(): void
    {
        $product = $this->repository->execute(id: (int) Request::get('produto_id'));
        $this->render('produtos/form.php', [
            'product' => $product->toArray(),
        ]);
    }
}
