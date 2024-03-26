<?php declare(strict_types=1);

namespace App\Product;

use App\Coin\CoinBucket;
use App\Coin\InvalidAmountForChangeException;
use App\Coin\UndeliverableChangeException;

abstract readonly class GenericProduct implements Product
{
    public function __construct(
        private ProductType $type,
        private int $price,
    ) {}

    public function is(ProductType $type): bool
    {
        return $this->type === $type;
    }

    public function type(): ProductType
    {
        return $this->type;
    }

    public function price(): int
    {
        return $this->price;
    }

    /**
     * @throws ProductNotAffordableException
     * @throws InvalidAmountForChangeException
     * @throws UndeliverableChangeException
     */
    public function buy(CoinBucket $payment, CoinBucket $changeBucket): CoinBucket
    {
        $priceDifference = $payment->value() - $this->price();

        if ($priceDifference < 0) {
            throw new ProductNotAffordableException($this, $payment);
        }

        $changeBucket->transfer($payment);

        return $changeBucket->getChange($priceDifference);
    }
}
