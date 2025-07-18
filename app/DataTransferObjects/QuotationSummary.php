<?php

namespace App\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;

readonly class QuotationSummary implements Arrayable
{
    public function __construct(
        public float $total,
        public string $currencyId,
        public int $quotationId
    ) {}

    public function toArray(): array
    {
        return [
            'total' => number_format($this->total, 2, '.', ''),
            'currency_id' => $this->currencyId,
            'quotation_id' => $this->quotationId,
        ];
    }
}