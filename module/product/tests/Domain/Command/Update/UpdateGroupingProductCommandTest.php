<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Command\Update;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Product\Domain\Command\Update\UpdateGroupingProductCommand;

/**
 */
class UpdateGroupingProductCommandTest extends TestCase
{
    /**
     * @param ProductId  $id
     * @param TemplateId $templateId
     * @param array      $categories
     *
     * @dataProvider dataProvider
     */
    public function testCreateCommand(ProductId $id, TemplateId $templateId, array $categories): void
    {
        $command = new UpdateGroupingProductCommand($id, $templateId, $categories);

        $this->assertSame($id, $command->getId());
        $this->assertSame($categories, $command->getCategories());
        $this->assertSame($templateId, $command->getTemplateId());
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    public function dataProvider(): array
    {
        return [
            [
                $this->createMock(ProductId::class),
                $this->createMock(TemplateId::class),
                [
                    $this->createMock(CategoryId::class),
                    $this->createMock(CategoryId::class),
                ],
            ],
        ];
    }
}
