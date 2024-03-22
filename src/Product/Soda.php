<?php declare(strict_types=1);

namespace App\Product;

final readonly class Soda extends GenericProduct
{
    public function __construct()
    {
        parent::__construct(
            type: ProductType::Soda,
            price: 35,
        );
    }
}
