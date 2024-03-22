<?php declare(strict_types=1);

namespace App\Product;

use App\Coin\Coin;

abstract readonly class GenericProduct implements Product
{
    abstract protected function getPrice(): int;

    public function getPriceDifference(Coin ...$coins): int
    {
        $providedCoinsValue = 0;

        foreach ($coins as $coin) {
            $providedCoinsValue += $coin->value;
        }

        return $providedCoinsValue - $this->getPrice();
    }
}
