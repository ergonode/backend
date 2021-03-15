<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\Command;

use Ergonode\BatchAction\Domain\Command\EndBatchActionCommand;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use PHPUnit\Framework\TestCase;

class EndBatchActionCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $batchActionId = $this->createMock(BatchActionId::class);

        $command = new EndBatchActionCommand($batchActionId);

        self::assertEquals($batchActionId, $command->getId());
    }
}
