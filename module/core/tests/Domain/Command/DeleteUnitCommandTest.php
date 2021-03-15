<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Domain\Command;

use Ergonode\Core\Domain\Command\DeleteUnitCommand;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use PHPUnit\Framework\TestCase;

class DeleteUnitCommandTest extends TestCase
{
    public function testCommand(): void
    {
        $id = $this->createMock(UnitId::class);

        $command = new DeleteUnitCommand($id);

        $this->assertEquals($id, $command->getId());
    }
}
