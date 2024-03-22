<?php declare(strict_types=1);

namespace Tests\Coin;

use App\Coin\Coin;
use App\Coin\CoinBucket;
use App\Coin\UndeliverableChangeException;
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

    public function testItAddsACoinInCorrectOrder(): void
    {
        $coinBucket = new CoinBucket(...Coin::cases());

        $coinBucket->addCoin(Coin::five);

        $this->assertSame(
            [
                Coin::twentyfive,
                Coin::ten,
                Coin::five,
                Coin::five,
                Coin::one,
            ],
            $coinBucket->coins()
        );
    }

    public function testItAddsACoinBucketInCorrectOrder(): void
    {
        $coinBucket = new CoinBucket(...Coin::cases());

        $coinBucket->addCoins(
            new CoinBucket(...Coin::cases())
        );

        $this->assertSame(
            [
                Coin::twentyfive,
                Coin::twentyfive,
                Coin::ten,
                Coin::ten,
                Coin::five,
                Coin::five,
                Coin::one,
                Coin::one,
            ],
            $coinBucket->coins()
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

    public function testItThrowsUndeliverableChangeExceptionIfAvailableCoinsCannotFormAnAmount(): void
    {
        $coinBucket = new CoinBucket(Coin::twentyfive, Coin::ten, Coin::five);
        $amount = 6;

        $this->expectExceptionObject(
            new UndeliverableChangeException($amount)
        );

        $coinBucket->getChange($amount);
    }

    public function testItReturnsChange(): void
    {
        $coinBucket = new CoinBucket(Coin::twentyfive, Coin::ten, Coin::five, Coin::one);
        $amount = 6;

        $change = $coinBucket->getChange($amount);

        $this->assertSame(
            [
                Coin::five,
                Coin::one,
            ],
            $change->coins()
        );

        $this->assertSame(
            [
                Coin::twentyfive,
                Coin::ten,
            ],
            $coinBucket->coins()
        );
    }
}