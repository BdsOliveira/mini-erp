<?php

namespace App\Modules\Catalog\Models;

readonly class Product
{
    public function __construct(
        public string $nome,
        public string $descricao,
        public float $preco,
        public int $status,
        public ?int $id = null,
        public ?string $imagem = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'preco' => $this->preco,
            'imagem' => $this->imagem,
            'status' => $this->status,
        ];
    }
}
