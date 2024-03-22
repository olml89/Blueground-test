<?php declare(strict_types=1);

namespace App\Product;

interface Product
{
    public function is(ProductType $type): bool;
    public function type(): ProductType;
    public function price(): int;
}
