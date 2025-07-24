<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\ProductsRepository;
use Framework\Http\Request;

class ProdutosController extends BaseController
{
    private $productsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productsRepository = new ProductsRepository();
    }
    public function index(): void
    {
        $products = $this->productsRepository->getAll();
        $this->render('produtos/index.php', ['products' => $products]);
    }

    public function create(): void
    {
        $this->render('produtos/form.php');
    }

    public function store(): void
    {
        $nome = Request::get('nome');
        $preco = (float) Request::get('preco');
        $descricao = Request::get('descricao');
        $imagem = Request::image('imagem');

        $this->productsRepository->save(name: $nome, price: $preco, description: $descricao, image: $imagem);

        $this->redirect('/produtos');
    }

    public function variants(): void
    {
        $productId = (int) Request::get('produto_id');

        $product = $this->productsRepository->getById(id: $productId);
        if (!$product) {
            $this->redirect('/not-found');
            return;
        }

        $variants = $this->productsRepository->getVariants(productId: $product['id']);

        $this->render('produtos/variants.php', [
            'product' => $product,
            'variants' => $variants,
        ]);
    }

    public function storeVariants(): void
    {
        $this->productsRepository->saveVariants(
            productId: (int) Request::get('produto_id'),
            type: Request::get('tipo'),
            values: Request::get('valor')
        );

        $this->redirect('/produtos/variacoes?produto_id=' . Request::get('produto_id'));
    }
}
