<?php declare(strict_types=1);

namespace App\Coin;

final class CoinBucket
{
    /**
     * Ordered from higher value coins to lower value coins
     *
     * @var Coin[] $coins
     */
    private array $coins;

    public function __construct(Coin ...$coins) {
        $this->coins = $coins;

        $this->order();
    }

    public function transfer(CoinBucket $coins): void
    {
        foreach ($coins->coins() as $coin) {
            $this->coins[] = $coin;
        }

        $coins->empty();
        $this->order();
    }

    private function empty(): void
    {
        $this->coins = [];
    }

    private function order(): void
    {
        usort(
            $this->coins,
            // Ordering in descending value
            fn (Coin $previous, Coin $next): int => $next->value - $previous->value,
        );
    }

    /**
     * @return Coin[]
     */
    public function coins(): array
    {
        return $this->coins;
    }

    public function value(): int
    {
        return array_reduce(
            $this->coins,
            fn (int $carry, Coin $coin): int => $carry + $coin->value,
            initial: 0,
        );
    }

    /**
     * @throws InvalidAmountForChangeException
     * @throws UndeliverableChangeException
     */
    public function getChange(int $amount): self
    {
        if ($amount < 0) {
            throw new InvalidAmountForChangeException($amount);
        }

        $change = new CoinBucket();

        if ($amount === 0) {
            return $change;
        }

        while ($coin = $this->popHighestCoinLowerOrEqualThan($amount)) {
            $change->addCoin($coin);
            $amount -= $coin->value;
        }

        if ($amount !== 0) {
            // Add the coins collected for the change back to the bucket
            $amount += $change->value();
            $this->transfer($change);

            throw new UndeliverableChangeException($amount);
        }

        return $change;
    }

    private function popHighestCoinLowerOrEqualThan(int $amount): ?Coin
    {
        if ($amount === 0) {
            return null;
        }

        foreach ($this->coins as $index => $coin) {
            if ($coin->value <= $amount) {
                // Remove the coin from this bucket and re-order
                unset($this->coins[$index]);
                $this->order();

                return $coin;
            }
        }

        return null;
    }

    private function addCoin(Coin $coin): void
    {
        $this->coins[] = $coin;

        $this->order();
    }
}
