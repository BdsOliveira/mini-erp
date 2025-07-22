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
}
