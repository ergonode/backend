<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Domain\Factory;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\Factory\CategoryFactory;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\TestCase;

class CategoryFactoryTest extends TestCase
{
    /**
     * @param array $attributes
     *
     * @dataProvider dataProvider
     */
    public function testCreation(
        CategoryId $id,
        CategoryCode $code,
        TranslatableString $name,
        array $attributes = []
    ): void {
        $factory = new CategoryFactory();
        $category = $factory->create($id, $code, $name, $attributes);
        $this->assertSame($id, $category->getId());
        $this->assertSame($code, $category->getCode());
        $this->assertSame($name, $category->getName());
        $this->assertSame($attributes, $category->getAttributes());
    }

    public function testCreationWithInvalidAttributeType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $id = $this->createMock(CategoryId::class);
        $code = $this->createMock(CategoryCode::class);
        $name = $this->createMock(TranslatableString::class);
        $attributes = ['key' => $this->createMock(\stdClass::class)];

        $factory = new CategoryFactory();
        $category = $factory->create($id, $code, $name, $attributes);
        $this->assertSame($id, $category->getId());
        $this->assertSame($code, $category->getCode());
        $this->assertSame($name, $category->getName());
        $this->assertSame($attributes, $category->getAttributes());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                $this->createMock(CategoryId::class),
                $this->createMock(CategoryCode::class),
                $this->createMock(TranslatableString::class),
                [],
            ],
            [
                $this->createMock(CategoryId::class),
                $this->createMock(CategoryCode::class),
                $this->createMock(TranslatableString::class),
                ['key' => $this->createMock(ValueInterface::class)],
            ],
        ];
    }
}
