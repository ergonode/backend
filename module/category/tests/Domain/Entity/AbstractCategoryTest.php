<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AbstractCategoryTest extends TestCase
{
    /**
     * @var CategoryId|MockObject
     */
    private $id;

    /**
     * @var CategoryCode|MockObject
     */
    private $code;

    /**
     * @var TranslatableString|MockObject
     */
    private $name;

    /**
     * @var ValueInterface[]|MockObject
     */
    private array $attributes;

    private AttributeCode $attributeCode;

    protected function setUp(): void
    {
        $this->id = $this->createMock(CategoryId::class);
        $this->code = $this->createMock(CategoryCode::class);
        $this->name = $this->createMock(TranslatableString::class);
        $this->attributeCode = $this->createMock(AttributeCode::class);
        $this->attributeCode->method('getValue')->willReturn('aaa');
        $this->attributes = [$this->attributeCode->getValue() => $this->createMock(ValueInterface::class)];
    }

    public function testCreateEntity(): void
    {
        /** @var AttributeCode|MockObject $attributeCode */
        $attributeCode = $this->createMock(AttributeCode::class);
        $attributeCode->method('getValue')->willReturn('bbb');
        /** @var ValueInterface|MockObject $attributeValue1 */
        $attributeValue1 = new StringValue('test1');
        /** @var ValueInterface|MockObject $attributeValue2 */
        $attributeValue2 = new StringValue('test2');
        /** @var TranslatableString|MockObject $name */
        $name = new TranslatableString(['en_GB' => 'test']);

        $entity = $this->getClass();

        self::assertEquals($this->id, $entity->getId());
        self::assertEquals($this->name, $entity->getName());
        self::assertEquals($this->code, $entity->getCode());
        self::assertEquals($this->attributes, $entity->getAttributes());

        self::assertTrue($entity->hasAttribute($this->attributeCode));
        self::assertEquals(reset($this->attributes), $entity->getAttribute($this->attributeCode));
        $entity->addAttribute($attributeCode, $attributeValue1);
        self::assertTrue($entity->hasAttribute($attributeCode));
        self::assertEquals($entity->getAttribute($attributeCode), $attributeValue1);
        $entity->changeAttribute($attributeCode, $attributeValue2);
        self::assertEquals($entity->getAttribute($attributeCode), $attributeValue2);
        self::assertArrayHasKey('aaa', ($entity->getAttributes()));
        self::assertArrayHasKey('bbb', ($entity->getAttributes()));
        $entity->removeAttribute($attributeCode);
        self::assertFalse($entity->hasAttribute($attributeCode));

        $entity->changeName($name);
        self::assertEquals($name, $entity->getName());
    }

    public function testAttributeNotFound(): void
    {
        $entity = $this->getClass();

        $this->expectException(\RuntimeException::class);
        $attributeCode = $this->createMock(AttributeCode::class);
        $entity->getAttribute($attributeCode);
    }

    public function testAttributeAlreadyExist(): void
    {
        $entity = $this->getClass();

        $this->expectException(\RuntimeException::class);
        $value = $this->createMock(ValueInterface::class);
        $entity->addAttribute($this->attributeCode, $value);
    }

    public function testChangingNotExistingAttribute(): void
    {
        $entity = $this->getClass();

        $this->expectException(\RuntimeException::class);
        $attributeCode = $this->createMock(AttributeCode::class);
        $value = $this->createMock(ValueInterface::class);
        $entity->changeAttribute($attributeCode, $value);
    }

    public function testRemovingNotExistingAttribute(): void
    {
        $entity = $this->getClass();

        $this->expectException(\RuntimeException::class);
        $attributeCode = $this->createMock(AttributeCode::class);
        $entity->removeAttribute($attributeCode);
    }

    private function getClass(): AbstractCategory
    {
        return new class(
            $this->id,
            $this->code,
            $this->name,
            $this->attributes,
        ) extends AbstractCategory {
            public function getType(): string
            {
                return 'TYPE';
            }
        };
    }
}
