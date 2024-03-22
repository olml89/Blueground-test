<?php declare(strict_types=1);

namespace App\Product;

use Exception;

final class ProductNotFoundException extends Exception
{
    public function __construct(ProductType $type)
    {
        parent::__construct(sprintf(
            'There are no products of type \'%s\' available',
            $type->value,
        ));
    }
}
