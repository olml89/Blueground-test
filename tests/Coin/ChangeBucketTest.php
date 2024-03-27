<?php declare(strict_types=1);

namespace Tests\Coin;

use App\Coin\ChangeBucket;
use App\Coin\Coin;
use App\Coin\InvalidAmountForChangeException;
use App\Coin\Payment;
use App\Coin\UndeliverableChangeException;
use PHPUnit\Framework\TestCase;

final class ChangeBucketTest extends TestCase
{
    public function testItThrowsInvalidAmountForChangeExceptionIfAmountIsNegative(): void
    {
        $changeBucket = new ChangeBucket();
        $payment = new Payment(...Coin::cases());
        $invalidAmount = -1;

        $this->expectExceptionObject(
            new InvalidAmountForChangeException($invalidAmount)
        );

        $changeBucket->getChange($payment, $invalidAmount);
    }

    public function testItReturnsEmptyChangeIfAmountIsZero(): void
    {
        $changeBucket = new ChangeBucket();
        $payment = new Payment(...Coin::cases());
        $paymentCoins = $payment->coins();
        $amount = 0;

        $change = $changeBucket->getChange($payment, $amount);

        $this->assertEmpty(
            $change->coins()
        );
        $this->assertSame(
            $paymentCoins,
            $changeBucket->coins()
        );
        $this->assertEmpty(
            $payment->coins()
        );
    }

    public function testItThrowsUndeliverableChangeExceptionAndRestoresCoinBucketAndPaymentIfAvailableCoinsCannotFormAnAmount(): void
    {
        $changeBucket = new ChangeBucket();
        $payment = new Payment(Coin::ten);
        $paymentCoins = $payment->coins();
        $amount = 6;

        $this->expectExceptionObject(
            new UndeliverableChangeException($amount)
        );

        try {
            $changeBucket->getChange($payment, $amount);
        }
        catch (UndeliverableChangeException $e) {
            $this->assertEmpty(
                $changeBucket->coins()
            );
            $this->assertSame(
                $paymentCoins,
                $payment->coins()
            );

            throw $e;
        }
    }

    public function testItReturnsChange(): void
    {
        $changeBucket = new ChangeBucket();
        $payment = new Payment(Coin::twentyfive, Coin::ten, Coin::five, Coin::one);
        $amount = 6;

        $change = $changeBucket->getChange($payment, $amount);

        $this->assertSame(
            [
                Coin::five,
                Coin::one,
            ],
            $change->coins()
        );
        $this->assertSame(
            [
                Coin::twentyfive,
                Coin::ten,
            ],
            $changeBucket->coins()
        );
        $this->assertEmpty(
            $payment->coins()
        );
    }
}
