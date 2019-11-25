<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Domain\Command;

use Ergonode\Condition\Domain\Command\CreateConditionSetCommand;
use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateConditionSetCommandTest extends TestCase
{
    /**
     * @param ConditionSetId $id
     * @param array          $conditions
     *
     * @dataProvider dataProvider
     */
    public function testUpdateSetCommand(ConditionSetId $id, array $conditions): void
    {
        $command = new CreateConditionSetCommand($id, $conditions);

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
