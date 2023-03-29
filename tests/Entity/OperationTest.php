<?php

namespace App\Tests\Entity;

use App\Entity\Operation;
use Brick\Money\Money;
use PHPUnit\Framework\TestCase;

class OperationTest extends TestCase
{
    public function testCreateSuccess(): void
    {
        $operation = new Operation('DE123456789', Money::of(1, 'EUR'));
        self::assertNotNull($operation->getId());
        self::assertEquals('DE123456789', $operation->getTaxNumber());
        self::assertEquals(1, $operation->getPrice()->getAmount()->toInt());
        self::assertEquals('EUR', $operation->getPrice()->getCurrency()->getCurrencyCode());
        self::assertEquals(0.19, $operation->getTax()->getAmount()->toFloat());
        self::assertEquals('EUR', $operation->getTax()->getCurrency()->getCurrencyCode());
        self::assertEquals(1.19, $operation->getTotal()->getAmount()->toFloat());
    }

    public function testCreateFail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $operation = new Operation('DE12345678', Money::of(1, 'EUR'));
    }
}
