<?php declare(strict_types=1);

namespace Tests\Coin;

use App\Coin\Coin;
use App\Coin\CoinBucket;
use PHPUnit\Framework\TestCase;

final class CoinBucketTest extends TestCase
{
    public function testItCreatesAnOrderedCoinBucket(): void
    {
        $coins = Coin::cases();
        shuffle($coins);

        $coinBucket = new CoinBucket(...$coins);

        $this->assertSame(
            [
                Coin::twentyfive,
                Coin::ten,
                Coin::five,
                Coin::one,
            ],
            $coinBucket->coins()
        );
    }

    public function testItTransfersToDestinationBucketAndOrdersIt(): void
    {
        $destinationCoinBucket = new CoinBucket();
        $sourceCoinBucket = new CoinBucket(...Coin::cases());

        $sourceCoinBucket->transferTo($destinationCoinBucket);

        $this->assertEmpty(
            $sourceCoinBucket->coins()
        );
        $this->assertSame(
            [
                Coin::twentyfive,
                Coin::ten,
                Coin::five,
                Coin::one,
            ],
            $destinationCoinBucket->coins()
        );
    }

    public function testItTransfersCoinToDestinationBucketAndOrdersIt(): void
    {
        $destinationCoinBucket = new CoinBucket();
        $sourceCoinBucket = new CoinBucket(...Coin::cases());

        $sourceCoinBucket->transferCoinTo(Coin::ten, $destinationCoinBucket);

        $this->assertSame(
            [
                Coin::twentyfive,
                Coin::five,
                Coin::one,
            ],
            $sourceCoinBucket->coins()
        );
        $this->assertSame(
            [
                Coin::ten,
            ],
            $destinationCoinBucket->coins()
        );
    }

    public function testItReturnsCorrectCoinsValue(): void
    {
        $coins = Coin::cases();
        $coinBucket = new CoinBucket(...$coins);

        $value = $coinBucket->value();

        $this->assertSame(
            array_reduce(
                $coins,
                fn (int $carry, Coin $coin): int => $carry + $coin->value,
                initial: 0,
            ),
            $value
        );
    }
}
