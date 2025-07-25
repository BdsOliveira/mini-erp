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
        $products = $this->productsRepository->getPaginated();
        $this->render('produtos/index.php', ['products' => $products]);
    }

    public function create(): void
    {
        $this->render('produtos/form.php');
    }

    public function edit(): void
    {
        $this->render('produtos/form.php', [
            'product' => $this->productsRepository->getById(id: (int) Request::get('produto_id')),
        ]);
    }

    public function update(): void
    {
        $this->productsRepository->update(
            productId: (int) Request::get('id'),
            nome: Request::get('nome'),
            preco: (float) Request::get('preco'),
            descricao: Request::get('descricao'),
            image: Request::image('imagem')
        );
        
        $this->redirect('/produtos');
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

    public function getProductVariant(): void
    {
        $productId = (int) Request::get('produto_id');
        $variantId = (int) Request::get('variant_id');

        $product = $this->productsRepository->getById(id: $productId);
        if (!$product) {
            $this->redirect('/not-found');
            return;
        }

        $variant = $this->productsRepository->getVariantById(productId: $productId, variantId: $variantId);
        if (!$variant) {
            $this->redirect('/not-found');
            return;
        }

        $this->render('produtos/edit-variant.php', [
            'product' => $product,
            'variant' => $variant,
            'estoque' => $this->productsRepository->getVariantStockQtd(variantId: $variantId),
        ]);
    }

    public function updateVariant()
    {
        $productId = (int) Request::get('produto_id');
        $variantId = (int) Request::get('variant_id');
        $tipo = (string) Request::get('tipo');
        $valor = (string) Request::get('valor');
        $estoque = (int) Request::get('estoque');

        $product = $this->productsRepository->getById(id: $productId);
        if (!$product) {
            $this->redirect('/not-found');
            return;
        }

        $variant = $this->productsRepository->getVariantById(productId: $productId, variantId: $variantId);
        if (!$variant) {
            $this->redirect('/not-found');
            return;
        }

        $variantId = $this->productsRepository->updateVariant(
            productId: $productId,
            variantId: $variantId,
            type: $tipo,
            value: $valor
        );

        $this->productsRepository->updateVariantStock(productId: $productId, variantId: $variantId, stock: $estoque);

        $this->redirect("/produtos/variacoes?produto_id=$productId");
    }
}
