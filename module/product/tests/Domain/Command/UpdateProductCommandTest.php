<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Product\Domain\Command\UpdateProductCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateProductCommandTest extends TestCase
{
    /**
     * @param ProductId $id
     * @param array     $categories
     *
     * @dataProvider dataProvider
     *
     */
    public function testCreateCommand(ProductId $id, array $categories): void
    {
        $command = new UpdateProductCommand($id, $categories);

        $this->assertSame($id, $command->getId());
        $this->assertSame($categories, $command->getCategories());
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
                [
                    $this->createMock(CategoryId::class),
                    $this->createMock(CategoryId::class),
                ],
            ],
        ];
    }
}
