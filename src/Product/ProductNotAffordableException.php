<?php declare(strict_types=1);

namespace App\Product;

use App\Coin\CoinBucket;
use Exception;

final class ProductNotAffordableException extends Exception
{
    public function __construct(Product $product, CoinBucket $payment)
    {
        parent::__construct(sprintf(
            'Product of type \'%s\' costs %s, %s provided',
            $product->type()->value,
            $product->price(),
            $payment->value(),
        ));
    }
}
