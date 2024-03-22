<?php declare(strict_types=1);

namespace App\Product;

use App\Coin\Coin;

interface Product
{
    public function getPriceDifference(Coin ...$coins): int;
}
