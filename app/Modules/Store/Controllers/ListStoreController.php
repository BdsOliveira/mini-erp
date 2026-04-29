<?php

declare(strict_types=1);

namespace App\Modules\Store\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Catalog\Repositories\Products\GetPaginatedRepository;
use Framework\Utils\Session;

class ListStoreController extends BaseController
{
    private $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new GetPaginatedRepository();
    }

    public function execute(): void
    {
        $products = $this->repository->execute();
        
        $this->render('loja/index.php', [
            'products' => $products,
            'flash_message' => Session::getFlash(),
        ]);
    }
}
