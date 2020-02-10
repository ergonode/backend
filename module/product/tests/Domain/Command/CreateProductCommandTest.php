<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Product\Domain\Command\CreateProductCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateProductCommandTest extends TestCase
{
    /**
     * @param ProductId $id
     * @param Sku       $sku
     * @param array     $categories
     * @param array     $attributes
     *
     * @dataProvider dataProvider
     *
     */
    public function testCreateCommand(ProductId $id, Sku $sku, array $categories, array $attributes): void
    {
        $command = new CreateProductCommand($id, $sku, $categories, $attributes);

        $this->assertSame($id, $command->getId());
        $this->assertSame($sku, $command->getSku());
        $this->assertSame($categories, $command->getCategories());
        $this->assertSame($attributes, $command->getAttributes());
        $this->assertNotNull($command->getId());
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
                $this->createMock(Sku::class),
                [
                    $this->createMock(CategoryId::class),
                    $this->createMock(CategoryId::class),
                ],
                [
                    'code1' => $this->createMock(ValueInterface::class),
                    'code2' => $this->createMock(ValueInterface::class),
                ],
            ],
        ];
    }
}
