<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Command;

use Ergonode\Condition\Domain\Command\UpdateConditionSetCommand;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use PHPUnit\Framework\TestCase;

class UpdateConditionSetCommandTest extends TestCase
{
    /**
     * @param array $conditions
     *
     * @dataProvider dataProvider
     */
    public function testUpdateSetCommand(ConditionSetId $id, array $conditions): void
    {
        $command = new UpdateConditionSetCommand($id, $conditions);

        $this->assertSame($id, $command->getId());
        $this->assertSame($conditions, $command->getConditions());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                $this->createMock(ConditionSetId::class),
                [],
            ],
            [
                $this->createMock(ConditionSetId::class),
                [$this->createMock(ConditionInterface::class)],
            ],
        ];
    }
}
