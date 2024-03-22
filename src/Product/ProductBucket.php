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

    public function add(Product $product): void
    {
        $this->products[] = $product;
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

    /**
     * @return Product[]
     */
    public function products(): array
    {
        return $this->products;
    }
}
