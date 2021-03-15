<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Domain\Command;

use Ergonode\Core\Domain\Command\CreateUnitCommand;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateUnitCommandTest extends TestCase
{
    public function testCommand(): void
    {
        $name = 'name';
        $symbol = 'symbol';
        $command = new CreateUnitCommand($name, $symbol);

        $this->assertEquals($symbol, $command->getSymbol());
        $this->assertEquals($name, $command->getName());
        $this->assertTrue(Uuid::isValid($command->getId()->getValue()));
    }
}
