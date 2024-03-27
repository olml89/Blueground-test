<?php declare(strict_types=1);

namespace Tests\Product;

use App\Coin\ChangeBucket;
use App\Coin\Coin;
use App\Coin\Payment;
use App\Product\Soda;
use Override;

final class SodaTest extends GenericProductTestCase
{
    #[Override]
    public function testItThrowsProductNotAffordableExceptionIfPaymentIsLowerThanThePrice(): void
    {
        $this->assertProductNotAffordableExceptionIsThrown(
            product: new Soda(),
        );
    }

    #[Override]
    public function testItIsBoughtWithExactPayment(): void
    {
        $this->assertPaymentIsEmptiedAndChangeIsCorrect(
            product: new Soda(),
            payment: new Payment(Coin::twentyfive, Coin::ten),
            changeBucket: new ChangeBucket()
        );
    }

    #[Override]
    public function testItIsBoughtWithPaymentHigherThanThePrice(): void
    {
        $this->assertPaymentIsEmptiedAndChangeIsCorrect(
            product: new Soda(),
            payment: new Payment(Coin::twentyfive, Coin::twentyfive),
            changeBucket: new ChangeBucket(Coin::ten, Coin::five)
        );
    }
}
