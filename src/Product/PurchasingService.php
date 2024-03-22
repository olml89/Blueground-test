<?php declare(strict_types=1);

namespace App\Product;

use App\Coin\Coin;
use InvalidArgumentException;

final class PurchasingService
{
    public function __construct(
        /**
         * We assume it is ordered from higher coins to lower coins
         *
         * @var Coin[]
         */
        private array $bucket,
    ) {}

    /**
     * @return Coin[]
     */
    public function getChange(Product $product, Coin ...$coins): array
    {
        $priceDifference = $product->getPriceDifference(...$coins);

        if ($priceDifference < 0) {
            throw new InvalidArgumentException('Not enough value');
        }

        if ($priceDifference === 0) {
            return [];
        }

        $this->bucket += $coins;
        $change = [];

        foreach ($this->bucket as $bucketCoin) {
            if ($bucketCoin->value >= $priceDifference) {
                $priceDifference -= $bucketCoin->value;
                $change[] = $bucketCoin;
            }
        }

        if ($priceDifference !== 0) {
            return $coins;
        }

        return $change;
    }
}
