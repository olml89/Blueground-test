<?php declare(strict_types=1);

namespace App\Coin;

use Exception;

final class UndeliverableChangeException extends Exception
{
    public function __construct(int $amount)
    {
        parent::__construct(sprintf(
            'Coins on the bucket are not capable to form an amount of \'%s\'',
            $amount,
        ));
    }
}
