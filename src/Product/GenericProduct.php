<?php declare(strict_types=1);

namespace App\Product;

use App\Coin\Coin;

abstract readonly class GenericProduct implements Product
{
    public function __construct(
        private ProductType $type,
        private int $price,
    ) {}

    public function is(ProductType $type): bool
    {
        return $this->type === $type;
    }

    public function type(): ProductType
    {
        return $this->type;
    }

    public function price(): int
    {
        return $this->price;
    }
}
