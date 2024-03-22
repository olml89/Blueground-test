<?php declare(strict_types=1);

namespace App\Product;

enum ProductType: string
{
    case Water = 'Water';
    case Soda = 'Soda';
    case Snack = 'Snack';
}
