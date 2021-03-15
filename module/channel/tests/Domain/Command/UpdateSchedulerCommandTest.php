<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Domain\Command;

use Ergonode\Channel\Domain\Command\UpdateSchedulerCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\AggregateId;

class UpdateSchedulerCommandTest extends TestCase
{
    public function testCreateCommand(): void
    {
        $id = $this->createMock(AggregateId::class);
        $active = false;
        $start = $this->createMock(\DateTime::class);
        $hour = 1;
        $minute = 1;

        $command = new UpdateSchedulerCommand($id, $active, $start, $hour, $minute);

        self::assertEquals($id, $command->getId());
        self::assertEquals($active, $command->isActive());
        self::assertEquals($start, $command->getStart());
        self::assertEquals($hour, $command->getHour());
        self::assertEquals($minute, $command->getMinute());
    }
}
