<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Domain\Factory;

use Ergonode\Core\Domain\Factory\UnitFactory;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use PHPUnit\Framework\TestCase;

class UnitFactoryTest extends TestCase
{
    public function testCreation(): void
    {
        $id = $this->createMock(UnitId::class);
        $name = 'name';
        $symbol = 'symbol';

        $factory = new UnitFactory();
        $unit = $factory->create($id, $name, $symbol);

        $this->assertSame($id, $unit->getId());
        $this->assertSame($name, $unit->getName());
        $this->assertSame($symbol, $unit->getSymbol());
    }
}
