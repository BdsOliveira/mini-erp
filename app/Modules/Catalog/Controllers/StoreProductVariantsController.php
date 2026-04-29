<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Catalog\Repositories\Variants\SaveVariantsRepository;
use Framework\Http\Request;

class StoreProductVariantsController extends BaseController
{
    private $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new SaveVariantsRepository();
    }

    public function execute(): void
    {
        $this->repository->execute(
            productId: (int) Request::get('produto_id'),
            type: Request::get('tipo'),
            values: Request::get('valor')
        );

        $this->redirect('/produtos/variacoes?produto_id=' . Request::get('produto_id'));
    }
}
