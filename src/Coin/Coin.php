<?php declare(strict_types=1);

namespace App\Coin;

use InvalidArgumentException;

enum Coin: int
{
    case one = 1;
    case five = 5;
    case ten = 10;
    case twentyfive = 25;
}
