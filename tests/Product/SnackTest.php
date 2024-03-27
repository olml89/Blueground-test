<?php declare(strict_types=1);

namespace Tests\Product;

use App\Coin\ChangeBucket;
use App\Coin\Coin;
use App\Coin\Payment;
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
            payment: new Payment(Coin::twentyfive, Coin::ten, Coin::ten),
            changeBucket: new ChangeBucket()
        );
    }

    #[Override]
    public function testItIsBoughtWithPaymentHigherThanThePrice(): void
    {
        $this->assertPaymentIsEmptiedAndChangeIsCorrect(
            product: new Snack(),
            payment: new Payment(Coin::twentyfive, Coin::twentyfive),
            changeBucket: new ChangeBucket(Coin::five)
        );
    }
}
