<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Domain\Factory;

use Ergonode\Core\Domain\Factory\UnitFactory;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use PHPUnit\Framework\TestCase;

/**
 */
class UnitFactoryTest extends TestCase
{
    /**
     */
    public function testCreation(): void
    {
        $id = $this->createMock(UnitId::class);
        $name = 'name';
        $symbol = 'symbol';

        $factory = new UnitFactory();
        $unit = $factory->create($id, $name, $symbol);

        self::assertSame($id, $unit->getId());
        self::assertSame($name, $unit->getName());
        self::assertSame($symbol, $unit->getSymbol());
    }
}
