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

    public function addCoins(CoinBucket $coins): void
    {
        foreach ($coins->coins() as $coin) {
            $this->coins[] = $coin;
        }

        $this->order();
    }

    public function addCoin(Coin $coin): void
    {
        $this->coins[] = $coin;

        $this->order();
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
     * @throws UndeliverableChangeException
     */
    public function getChange(int $amount): self
    {
        $change = new CoinBucket();

        foreach ($this->coins as $index => $coin) {
            if ($coin->value <= $amount) {
                $change->addCoin($coin);
                $amount -= $coin->value;

                unset($this->coins[$index]);
            }
        }

        if ($amount !== 0) {
            $this->addCoins($change);

            throw new UndeliverableChangeException($amount + $change->value());
        }

        return $change;
    }
}
