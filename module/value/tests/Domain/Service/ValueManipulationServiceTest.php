<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Tests\Domain\Service;

use Ergonode\Value\Domain\Service\ValueManipulationService;
use Ergonode\Value\Domain\Service\ValueUpdateStrategyInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\TestCase;

class ValueManipulationServiceTest extends TestCase
{
    public function testCalculation(): void
    {
        $strategy = $this->createMock(ValueUpdateStrategyInterface::class);
        $strategy->method('isSupported')->willReturn(true);
        $strategy->expects($this->once())->method('calculate');
        $oldValue = $this->createMock(ValueInterface::class);
        $newValue = $this->createMock(ValueInterface::class);
        $service = new ValueManipulationService(...[$strategy]);

        $service->calculate($oldValue, $newValue);
    }

    public function testNoStrategy(): void
    {
        $this->expectException(\RuntimeException::class);
        $strategy = $this->createMock(ValueUpdateStrategyInterface::class);
        $strategy->method('isSupported')->willReturn(false);
        $oldValue = $this->createMock(ValueInterface::class);
        $newValue = $this->createMock(ValueInterface::class);
        $service = new ValueManipulationService(...[$strategy]);

        $service->calculate($oldValue, $newValue);
    }
}
