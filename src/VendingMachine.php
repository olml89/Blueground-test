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
    public function select(ProductType $type, CoinBucket $payment): SelectionResult
    {
        $product = $this->productBucket->select($type);
        $priceDifference = $payment->value() - $product->price();

        if ($priceDifference < 0) {
            // Add the product back to the bucket
            $this->productBucket->add($product);

            throw new ProductNotAffordableException($product, $payment);
        }

        $this->coinBucket->transfer($payment);

        return new SelectionResult(
            product: $product,
            change: $this->coinBucket->getChange($priceDifference)
        );
    }
}
