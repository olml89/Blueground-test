<?php declare(strict_types=1);

namespace App\Coin;

final class Payment extends CoinBucket
{
    /**
     * @throws InvalidEmptyPaymentException
     */
    public function __construct(Coin ...$coins)
    {
        if (empty($coins)) {
            throw new InvalidEmptyPaymentException();
        }

        parent::__construct(...$coins);
    }
}
