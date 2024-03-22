<?php declare(strict_types=1);

namespace App\Product;

final readonly class Snack extends GenericProduct
{
    public function __construct()
    {
        parent::__construct(
            type: ProductType::Snack,
            price: 45,
        );
    }
}
