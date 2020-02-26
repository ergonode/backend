<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Category\Domain\Command\Tree;

use Ergonode\Category\Application\Model\Tree\TreeNodeFormModel;
use Ergonode\Category\Domain\Command\Tree\CreateTreeCommand;
use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateTreeCommandTest extends TestCase
{
    /**
     * @param TranslatableString $name
     * @param string             $code
     * @param array              $categories
     *
     * @dataProvider dataProvider
     */
    public function testCreateTreeCommand(
        TranslatableString $name,
        string $code,
        array $categories
    ): void {
        $command = new CreateTreeCommand($code, $name, $categories);
        $this->assertSame($name, $command->getName());
        $this->assertSame($code, $command->getCode());
        foreach ($command->getCategories() as $category) {
            $this->assertInstanceOf(Node::class, $category);
        }
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

        return [
            [
                $this->createMock(TranslatableString::class),
                'string',
                [$node1],
            ],
        ];
    }
}
