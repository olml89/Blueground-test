<?php declare(strict_types=1);

namespace App\Coin;

class CoinBucket
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

    public function transferTo(CoinBucket $destinationCoinBucket): void
    {
        foreach ($this->coins as $index => $coin) {
            $destinationCoinBucket->addCoin($coin);
            $this->removeCoin($index);
        }

        $this->empty();
        $destinationCoinBucket->order();
    }

    public function transferCoinTo(Coin $transferableCoin, CoinBucket $destinationCoinBucket): void
    {
        foreach ($this->coins as $index => $coin) {
            if ($coin === $transferableCoin) {
                $destinationCoinBucket->addCoin($coin);
                $this->removeCoin($index);
            }
        }

        $this->order();
        $destinationCoinBucket->order();
    }

    private function addCoin(Coin $coin): void
    {
        $this->coins[] = $coin;
    }

    private function removeCoin(int $index): void
    {
        unset($this->coins[$index]);
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
}
