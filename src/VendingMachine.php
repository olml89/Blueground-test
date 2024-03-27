<?php declare(strict_types=1);

namespace App;

use App\Coin\ChangeBucket;
use App\Coin\InvalidAmountForChangeException;
use App\Coin\Payment;
use App\Coin\UndeliverableChangeException;
use App\Product\ProductBucket;
use App\Product\ProductNotAffordableException;
use App\Product\ProductNotFoundException;
use App\Product\ProductType;

final readonly class VendingMachine
{
    public function __construct(
        private ProductBucket $productBucket,
        private ChangeBucket $changeBucket,
    ) {}

    /**
     * @throws ProductNotFoundException
     * @throws ProductNotAffordableException
     * @throws InvalidAmountForChangeException
     * @throws UndeliverableChangeException
     */
    public function buy(ProductType $type, Payment $payment): SelectionResult
    {
        $product = $this->productBucket->select($type);
        $change = $product->buy($payment, $this->changeBucket);
        $this->productBucket->release($product);

        return new SelectionResult($product, $change);
    }
}
