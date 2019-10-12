<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Condition\Domain\Command;

use Ergonode\Condition\Domain\Command\UpdateConditionSetCommand;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateConditionSetCommandTest extends TestCase
{
    /**
     * @param ConditionSetId     $id
     * @param array              $conditions
     * @param TranslatableString $name
     * @param TranslatableString $description
     *
     * @dataProvider dataProvider
     */
    public function testUpdateSetCommand(
        ConditionSetId $id,
        array $conditions,
        TranslatableString $name,
        TranslatableString $description
    ): void {
        $command = new UpdateConditionSetCommand($id, $conditions, $name, $description);

        $this->assertSame($id, $command->getId());
        $this->assertSame($conditions, $command->getConditions());
        $this->assertSame($name, $command->getName());
        $this->assertTrue($command->hasName());
        $this->assertSame($description, $command->getDescription());
        $this->assertTrue($command->hasDescription());
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
                $this->createMock(TranslatableString::class),
                $this->createMock(TranslatableString::class),
            ],
        ];
    }
}
