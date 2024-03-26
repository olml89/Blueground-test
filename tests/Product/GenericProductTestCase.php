<?php declare(strict_types=1);

namespace Tests\Product;

use App\Coin\CoinBucket;
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
        // Empty payment
        $payment = new CoinBucket();

        $this->expectExceptionObject(
            new ProductNotAffordableException($product, $payment)
        );

        $product->buy($payment, new CoinBucket());
    }

    protected function assertPaymentIsEmptiedAndChangeIsCorrect(Product $product, CoinBucket $payment, CoinBucket $changeBucket): void
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
