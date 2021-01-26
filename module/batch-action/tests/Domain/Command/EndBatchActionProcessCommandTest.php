<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\Command;

use Ergonode\BatchAction\Domain\Command\EndBatchActionProcessCommand;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use PHPUnit\Framework\TestCase;

class EndBatchActionProcessCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $batchActionId = $this->createMock(BatchActionId::class);
        $type = $this->createMock(BatchActionType::class);

        $command = new EndBatchActionProcessCommand($batchActionId, $type);

        self::assertEquals($batchActionId, $command->getId());
        self::assertEquals($type, $command->getType());
    }
}
