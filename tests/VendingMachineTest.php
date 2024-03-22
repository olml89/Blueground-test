<?php declare(strict_types=1);

namespace tests;

use App\Coin\Coin;
use App\Coin\CoinBucket;
use App\Product\ProductBucket;
use App\Product\ProductNotAffordableException;
use App\Product\ProductType;
use App\Product\Snack;
use App\Product\Soda;
use App\Product\Water;
use App\VendingMachine;
use PHPUnit\Framework\TestCase;

final class VendingMachineTest extends TestCase
{
    private readonly Water $waterProduct;
    private readonly ProductBucket $productBucket;
    private readonly VendingMachine $vendingMachine;

    protected function setUp(): void
    {
        parent::setUp();

        $this->waterProduct = new Water();
        $this->productBucket = new ProductBucket($this->waterProduct);

        $this->vendingMachine = new VendingMachine(
            productBucket: $this->productBucket,
            coinBucket: new CoinBucket(...Coin::cases())
        );
    }

    public function testItThrowsProductNotAffordableExceptionIfASelectedProductHasAValueHigherThanThePayment(): void
    {
        $payment = new CoinBucket(Coin::ten);
        $selectedProductType = ProductType::Water;

        $this->expectExceptionObject(
            new ProductNotAffordableException(
                product: $this->waterProduct,
                payment: $payment,
            )
        );

        $this->vendingMachine->select($selectedProductType, $payment);

        $this->assertEquals(
            [
                $this->waterProduct,
            ],
            $this->productBucket->products()
        );
    }

    public function testItReturnsAProductWithoutChangeIfThePaymentIsExact(): void
    {
        $payment = new CoinBucket(Coin::ten, Coin::ten, Coin::five);
        $selectedProductType = ProductType::Water;

        $selectionResult = $this->vendingMachine->select($selectedProductType, $payment);

        $this->assertSame($this->waterProduct, $selectionResult->product);
        $this->assertEquals(0, $selectionResult->change->value());
        $this->assertEmpty($this->productBucket->products());
    }

    public function testItReturnsAProductWithChangeIfThePaymentIsHigherThanTheProductValue(): void
    {
        $payment = new CoinBucket(Coin::ten, Coin::ten, Coin::ten);
        $selectedProductType = ProductType::Water;

        $selectionResult = $this->vendingMachine->select($selectedProductType, $payment);

        $this->assertSame($this->waterProduct, $selectionResult->product);
        $this->assertEquals(5, $selectionResult->change->value());
        $this->assertEmpty($this->productBucket->products());
    }
}
