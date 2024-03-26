<?php declare(strict_types=1);

namespace App\Product;

use App\Coin\CoinBucket;
use App\Coin\InvalidAmountForChangeException;
use App\Coin\UndeliverableChangeException;

interface Product
{
    public function is(ProductType $type): bool;
    public function type(): ProductType;
    public function price(): int;

    /**
     * It takes the payment and the coin bucket as inputs.
     * It returns the change as output.
     *
     * @throws ProductNotAffordableException
     * @throws InvalidAmountForChangeException
     * @throws UndeliverableChangeException
     */
    public function buy(CoinBucket $payment, CoinBucket $changeBucket): CoinBucket;
}
