<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Domain\Command\Schedule;

use Ergonode\Channel\Domain\Command\Schedule\ScheduleCommand;
use PHPUnit\Framework\TestCase;

class ScheduleCommandTest extends TestCase
{
    public function testCreateCommand(): void
    {
        $date = $this->createMock(\DateTime::class);

        $command = new ScheduleCommand($date);

        self::assertEquals($date, $command->getDate());
    }
}
