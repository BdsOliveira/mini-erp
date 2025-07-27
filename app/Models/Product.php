<?php

namespace App\Models;

readonly class Product
{
    public function __construct(
        public ?int $id = 0,
        public string $nome,
        public string $descricao,
        public float $preco,
        public ?string $imagem = '',
        public int $status,
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
