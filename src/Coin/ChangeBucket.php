<?php declare(strict_types=1);

namespace App\Coin;

final class ChangeBucket extends CoinBucket
{
    /**
     * @throws InvalidAmountForChangeException
     * @throws UndeliverableChangeException
     */
    public function getChange(Payment $payment, int $amount): CoinBucket
    {
        if ($amount < 0) {
            throw new InvalidAmountForChangeException($amount);
        }

        $originalPayment = clone $payment;
        $payment->transferTo($this);
        $change = new CoinBucket();

        if ($amount === 0) {
            return $change;
        }

        foreach ($this->coins() as $coin) {
            if ($amount === 0) {
                break;
            }

            // Fill the change
            if ($coin->value <= $amount) {
                $amount -= $coin->value;
                $this->transferCoinTo($coin, $change);
            }
        }

        if ($amount !== 0) {
            // Add the coins collected for the change back to the change bucket
            $amount += $change->value();
            $change->transferTo($this);

            // Restore the original payment
            foreach ($originalPayment->coins() as $coin) {
                $this->transferCoinTo($coin, $payment);
            }

            throw new UndeliverableChangeException($amount);
        }

        return $change;
    }
}
