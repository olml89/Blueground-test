<?php declare(strict_types=1);

namespace Tests;

use App\Coin\Coin;
use App\Coin\ChangeBucket;
use App\Coin\Payment;
use App\Product\ProductBucket;
use App\Product\ProductType;
use App\Product\Water;
use App\VendingMachine;
use PHPUnit\Framework\TestCase;

final class VendingMachineTest extends TestCase
{
    public function testItReleasesAProductAndChange(): void
    {
        $water = new Water();
        $productBucket = new ProductBucket($water);

        $vendingMachine = new VendingMachine(
            productBucket: $productBucket,
            changeBucket: new ChangeBucket(...Coin::cases())
        );

        $payment = new Payment(Coin::ten, Coin::ten, Coin::ten);
        $selectedProductType = ProductType::Water;

        $selectionResult = $vendingMachine->buy($selectedProductType, $payment);

        $this->assertEmpty(
            $productBucket->products()
        );
        $this->assertEmpty(
            $payment->coins()
        );
        $this->assertSame(
            $water,
            $selectionResult->product
        );
        $this->assertEquals(
            5,
            $selectionResult->change->value()
        );
    }
}
