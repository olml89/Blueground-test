<?php declare(strict_types=1);

namespace App;

use App\Coin\CoinBucket;
use App\Product\Product;

final readonly class SelectionResult
{
    public function __construct(
        public Product $product,
        public CoinBucket $change,
    ) {}
}
