<?php declare(strict_types=1);

namespace App\Product;

final readonly class Snack extends GenericProduct
{
    public function getPrice(): int
    {
        return 45;
    }
}
