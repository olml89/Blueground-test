<?php declare(strict_types=1);

namespace App\Product;

final readonly class Water extends GenericProduct
{
    public function __construct()
    {
        parent::__construct(
            type: ProductType::Water,
            price: 25,
        );
    }
}
