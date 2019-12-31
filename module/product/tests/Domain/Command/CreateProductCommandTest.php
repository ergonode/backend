<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Command;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\Command\CreateProductCommand;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateProductCommandTest extends TestCase
{
    /**
     * @param Sku        $sku
     * @param TemplateId $templateId
     * @param array      $categories
     * @param array      $attributes
     *
     * @dataProvider dataProvider
     *
     * @throws \Exception
     */
    public function testCreateCommand(Sku $sku, TemplateId $templateId, array $categories, array $attributes): void
    {
        $command = new CreateProductCommand($sku, $categories, $attributes);

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
                $this->createMock(Sku::class),
                $this->createMock(TemplateId::class),
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
