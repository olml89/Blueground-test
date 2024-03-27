<?php declare(strict_types=1);

namespace App\Coin;

use Exception;

final class InvalidEmptyPaymentException extends Exception
{
    public function __construct()
    {
        parent::__construct('A payment cannot be empty');
    }
}
