<?php declare(strict_types=1);

namespace App\Coin;

use Exception;

final class InvalidAmountForChangeException extends Exception
{
    public function __construct(int $amount)
    {
        parent::__construct(sprintf(
            'Amount for change must be positive, %s provided',
            $amount,
        ));
    }
}
