<?php declare(strict_types=1);

namespace Tests\Product;

use App\Product\ProductBucket;
use App\Product\ProductNotFoundException;
use App\Product\ProductType;
use App\Product\Snack;
use App\Product\Soda;
use App\Product\Water;
use PHPUnit\Framework\TestCase;

final class ProductBucketTest extends TestCase
{
    public function testItThrowsProductNotFoundExceptionIfProductDoesNotExist(): void
    {
        $productBucket = new ProductBucket(
            new Water(),
            new Soda(),
        );
        $selectedProductType = ProductType::Snack;

        $this->expectExceptionObject(
            new ProductNotFoundException($selectedProductType)
        );

        $productBucket->select($selectedProductType);
    }

    public function testItReturnsAProductOfTheSelectedProductType(): void
    {
        $productBucket = new ProductBucket(
            $soda = new Soda(),
            $snack = new Snack(),
            $water = new Water(),
        );
        $selectedProductType = ProductType::Snack;

        $product = $productBucket->select($selectedProductType);

        $this->assertInstanceOf(Snack::class, $product);

        $this->assertSame(
            [
                $soda,
                $snack,
                $water,
            ],
            $productBucket->products()
        );
    }

    public function testItReleasesAProduct(): void
    {
        $productBucket = new ProductBucket(
            $soda = new Soda(),
            $snack = new Snack(),
            $water = new Water(),
        );

        $productBucket->release($snack);

        $this->assertSame(
            [
                $soda,
                $water,
            ],
            $productBucket->products()
        );
    }
}
