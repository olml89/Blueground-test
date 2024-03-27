<?php declare(strict_types=1);

namespace Tests\Product;

use App\Coin\ChangeBucket;
use App\Coin\Coin;
use App\Coin\Payment;
use App\Product\Product;
use App\Product\ProductNotAffordableException;
use PHPUnit\Framework\TestCase;

abstract class GenericProductTestCase extends TestCase
{
    abstract public function testItThrowsProductNotAffordableExceptionIfPaymentIsLowerThanThePrice(): void;
    abstract public function testItIsBoughtWithExactPayment(): void;
    abstract public function testItIsBoughtWithPaymentHigherThanThePrice(): void;

    protected function assertProductNotAffordableExceptionIsThrown(Product $product): void
    {
        $payment = new Payment(Coin::one);

        $this->expectExceptionObject(
            new ProductNotAffordableException($product, $payment)
        );

        $product->buy($payment, new ChangeBucket());
    }

    protected function assertPaymentIsEmptiedAndChangeIsCorrect(Product $product, Payment $payment, ChangeBucket $changeBucket): void
    {
        $expectedChangeValue = $payment->value() - $product->price();

        $change = $product->buy($payment, $changeBucket);

        $this->assertEmpty(
            $payment->coins()
        );
        $this->assertEquals(
            $expectedChangeValue,
            $change->value(),
        );

        foreach ($change->coins() as $coin) {
            $this->assertFalse(
                array_search(
                    needle: $coin,
                    haystack: $changeBucket->coins(),
                    strict: true,
                )
            );
        }
    }
}
