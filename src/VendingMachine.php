<?php declare(strict_types=1);

namespace App;

use App\Coin\CoinBucket;
use App\Coin\InvalidAmountForChangeException;
use App\Coin\UndeliverableChangeException;
use App\Product\ProductBucket;
use App\Product\ProductNotAffordableException;
use App\Product\ProductNotFoundException;
use App\Product\ProductType;

final readonly class VendingMachine
{
    public function __construct(
        private ProductBucket $productBucket,
        private CoinBucket $coinBucket,
    ) {}

    /**
     * @throws ProductNotFoundException
     * @throws ProductNotAffordableException
     * @throws InvalidAmountForChangeException
     * @throws UndeliverableChangeException
     */
    public function buy(ProductType $type, CoinBucket $payment): SelectionResult
    {
        $product = $this->productBucket->select($type);
        $change = $product->buy($payment, $this->coinBucket);
        $this->productBucket->release($product);

        return new SelectionResult($product, $change);
    }
}
