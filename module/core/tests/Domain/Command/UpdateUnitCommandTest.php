<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Domain\Command;

use Ergonode\Core\Domain\Command\UpdateUnitCommand;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateUnitCommandTest extends TestCase
{
    /**
     */
    public function testCommand()
    {
        $id = $this->createMock(UnitId::class);
        $name = 'name';
        $symbol = 'symbol';

        $command = new UpdateUnitCommand($id, $name, $symbol);

        self::assertEquals($id, $command->getId());
        self::assertEquals($name, $command->getName());
        self::assertEquals($symbol, $command->getSymbol());
    }
}
