<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Condition\Domain\Command\CreateConditionSetCommand;
use Ergonode\Condition\Domain\Condition\ProductBelongCategoryCondition;
use Ergonode\Condition\Domain\Condition\ProductCompletenessCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
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

    /**
     */
    public function testCreateProductBelong(): void
    {
        $categoryId = $this->createMock(CategoryId::class);
        $conditions = [
            new ProductCompletenessCondition('complete'),
            new ProductBelongCategoryCondition($categoryId, 'equal'),
        ];

        $id = ConditionSetId::generate();

        $command = new CreateConditionSetCommand($id, $conditions);

        $this->assertSame($id, $command->getId());
        $this->assertSame($conditions, $command->getConditions());
    }
}
