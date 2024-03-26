<?php declare(strict_types=1);

namespace Tests\Product;

use App\Coin\Coin;
use App\Coin\CoinBucket;
use App\Product\Water;
use Override;

final class WaterTest extends GenericProductTestCase
{
    #[Override]
    public function testItThrowsProductNotAffordableExceptionIfPaymentIsLowerThanThePrice(): void
    {
        $this->assertProductNotAffordableExceptionIsThrown(
            product: new Water(),
        );
    }

    #[Override]
    public function testItIsBoughtWithExactPayment(): void
    {
        $this->assertPaymentIsEmptiedAndChangeIsCorrect(
            product: new Water(),
            payment: new CoinBucket(Coin::ten, Coin::ten, Coin::five),
            changeBucket: new CoinBucket()
        );
    }

    #[Override]
    public function testItIsBoughtWithPaymentHigherThanThePrice(): void
    {
        $this->assertPaymentIsEmptiedAndChangeIsCorrect(
            product: new Water(),
            payment: new CoinBucket(Coin::ten, Coin::ten, Coin::ten),
            changeBucket: new CoinBucket(Coin::five)
        );
    }
}
