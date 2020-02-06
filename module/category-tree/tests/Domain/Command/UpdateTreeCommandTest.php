<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\CategoryTree\Domain\Command;

use Ergonode\CategoryTree\Application\Model\TreeNodeFormModel;
use Ergonode\CategoryTree\Domain\Command\UpdateTreeCommand;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateTreeCommandTest extends TestCase
{
    /**
     * @param CategoryTreeId     $id
     * @param TranslatableString $name
     * @param array              $categories
     *
     * @dataProvider dataProvider
     */
    public function testUpdateTreeCommand(
        CategoryTreeId $id,
        TranslatableString $name,
        array $categories
    ): void {
        $command = new UpdateTreeCommand($id, $name, $categories);
        $this->assertSame($id, $command->getId());
        $this->assertSame($name, $command->getName());
        $this->containsOnlyInstancesOf(TreeNodeFormModel::class, $command->getCategories());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        $node1 = $this->createMock(TreeNodeFormModel::class);
        $node2 = $this->createMock(TreeNodeFormModel::class);
        $node1->categoryId = '350ba9cc-773b-4ae0-9aa5-ea7efe822e60';
        $node2->categoryId = '350ba9cc-773b-4ae0-9aa5-ea7efe822e61';
        $node2->childrens = [];
        $node1->childrens = [$node2];

        return
            [
                [
                    $this->createMock(CategoryTreeId::class),
                    $this->createMock(TranslatableString::class),
                    [$node1],
                ],
            ];
    }
}
