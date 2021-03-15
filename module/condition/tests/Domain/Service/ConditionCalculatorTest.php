<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Service;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Condition\Domain\Service\ConditionCalculator;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Condition\Infrastructure\Provider\ConditionCalculatorProvider;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use PHPUnit\Framework\TestCase;

class ConditionCalculatorTest extends TestCase
{
    private ConditionCalculatorProvider $provider;

    private ConditionSet $conditionSet;

    private AbstractProduct $product;


    protected function setUp(): void
    {
        $this->provider = $this->createMock(ConditionCalculatorProvider::class);
        $condition = $this->createMock(ConditionInterface::class);
        $condition->method('getType')->willReturn('TYPE');
        $this->conditionSet = $this->createMock(ConditionSet::class);
        $this->conditionSet->method('getConditions')->willReturn([$condition]);
        $this->product = $this->createMock(AbstractProduct::class);
    }

    public function testConditionCalculationTrue(): void
    {
        $calculator = $this->createMock(ConditionCalculatorStrategyInterface::class);
        $calculator->method('calculate')->willReturn(true);
        $this->provider->method('provide')->willReturn($calculator);
        $conditionCalculator = new ConditionCalculator($this->provider);
        $this->assertTrue($conditionCalculator->calculate($this->conditionSet, $this->product));
    }

    public function testConditionCalculationFalse(): void
    {
        $calculator = $this->createMock(ConditionCalculatorStrategyInterface::class);
        $calculator->method('calculate')->willReturn(false);
        $this->provider->method('provide')->willReturn($calculator);
        $conditionCalculator = new ConditionCalculator($this->provider);
        $this->assertFalse($conditionCalculator->calculate($this->conditionSet, $this->product));
    }
}
