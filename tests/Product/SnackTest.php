<?php declare(strict_types=1);

namespace Tests\Product;

use App\Coin\Coin;
use App\Coin\CoinBucket;
use App\Product\Snack;
use Override;

final class SnackTest extends GenericProductTestCase
{
    #[Override]
    public function testItThrowsProductNotAffordableExceptionIfPaymentIsLowerThanThePrice(): void
    {
        $this->assertProductNotAffordableExceptionIsThrown(
            product: new Snack(),
        );
    }

    #[Override]
    public function testItIsBoughtWithExactPayment(): void
    {
        $this->assertPaymentIsEmptiedAndChangeIsCorrect(
            product: new Snack(),
            payment: new CoinBucket(Coin::twentyfive, Coin::ten, Coin::ten),
            changeBucket: new CoinBucket()
        );
    }

    #[Override]
    public function testItIsBoughtWithPaymentHigherThanThePrice(): void
    {
        $this->assertPaymentIsEmptiedAndChangeIsCorrect(
            product: new Snack(),
            payment: new CoinBucket(Coin::twentyfive, Coin::twentyfive),
            changeBucket: new CoinBucket(Coin::five)
        );
    }
}
