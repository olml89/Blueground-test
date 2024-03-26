<?php declare(strict_types=1);

namespace App\Product;

final class ProductBucket
{
    /**
     * @var Product[] $products
     */
    private array $products;

    public function __construct(Product ...$products)
    {
        $this->products = $products;
    }

    /**
     * @throws ProductNotFoundException
     */
    public function select(ProductType $type): Product
    {
        foreach ($this->products as $index => $product) {
            if ($product->is($type)) {
                // Unset the selected product and re-index
                unset($this->products[$index]);
                $this->products = array_values($this->products);

                return $product;
            }
        }

        throw new ProductNotFoundException($type);
    }

    public function release(Product $selectedProduct): void
    {
        foreach ($this->products as $index => $product) {
            if ($product === $selectedProduct) {
                // Unset the selected product and re-index
                unset($this->products[$index]);
                $this->products = array_values($this->products);

                return;
            }
        }
    }

    /**
     * @return Product[]
     */
    public function products(): array
    {
        return $this->products;
    }
}
