<?php declare(strict_types=1);

namespace App\Product;

use App\Coin\ChangeBucket;
use App\Coin\Payment;
use Exception;

final class ProductNotAffordableException extends Exception
{
    public function __construct(Product $product, Payment $payment)
    {
        parent::__construct(sprintf(
            'Product of type \'%s\' costs %s, %s provided',
            $product->type()->value,
            $product->price(),
            $payment->value(),
        ));
    }
}
