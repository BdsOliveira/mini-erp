<?php

namespace App\Modules\Catalog\Models;

readonly class Variant
{
    public function __construct(
        public int $produto_id,
        public string $tipo,
        public string $valor,
        public string $sku,
        public ?int $id = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'produto_id' => $this->produto_id,
            'tipo' => $this->tipo,
            'valor' => $this->valor,
            'sku' => $this->sku,
        ];
    }
}
