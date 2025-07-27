<?php

namespace App\Models;

readonly class ProductDTO
{
    private int $id;
    private string $nome;
    private string $descricao;
    private float $preco;
    private string $imagem;
    private int $status;

    public function __construct(
        private array $data
    ) {
        $this->id = (int) $data['id'];
        $this->nome = $data['nome'];
        $this->descricao = $data['descricao'] ?? '';
        $this->preco = (float) $data['preco'] ?? 0;
        $this->imagem = $data['imagem'] ?? '';
        $this->status = (int) $data['status'] ?? 1;
    }

    public function fromArray(): Product
    {
        return new Product(
            id: $this->id,
            nome: $this->nome,
            descricao: $this->descricao,
            preco: $this->preco,
            imagem: $this->imagem,
            status: $this->status
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->data['id'],
            'nome' => $this->data['nome'],
            'descricao' => $this->data['descricao'],
            'preco' => $this->data['preco'],
            'imagem' => $this->data['imagem'],
            'status' => $this->data['status'],
        ];
    }
}
