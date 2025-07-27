<?php

namespace App\Models;

readonly class VariantDTO
{
    private int $id;
    private int $produto_id;
    private string $tipo;
    private string $valor;
    private string $sku;

    public function __construct(
        private array $data
    ) {
        $this->id = (int) $data['id'];
        $this->produto_id = (int) $data['produto_id'];
        $this->tipo = $data['tipo'] ?? '';
        $this->valor = $data['valor'] ?? '';
        $this->sku = $data['sku'] ?? '';
    }

    public function fromArray(): Variant
    {
        return new Variant(
            id: $this->id,
            produto_id: $this->produto_id,
            tipo: $this->tipo,
            valor: $this->valor,
            sku: $this->sku,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->data['id'],
            'produto_id' => $this->data['produto_id'],
            'tipo' => $this->data['tipo'],
            'valor' => $this->data['valor'],
            'sku' => $this->data['sku'],
        ];
    }
}
