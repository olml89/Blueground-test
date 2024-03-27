<?php declare(strict_types=1);

namespace Tests\Coin;

use App\Coin\InvalidEmptyPaymentException;
use App\Coin\Payment;
use PHPUnit\Framework\TestCase;

final class PaymentTest extends TestCase
{
    public function testItDoesNotAllowEmptyPayments(): void
    {
        $this->expectExceptionObject(
            new InvalidEmptyPaymentException()
        );

        new Payment();
    }
}
