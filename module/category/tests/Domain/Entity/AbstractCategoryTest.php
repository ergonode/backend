<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

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
use Symfony\Bundle\MakerBundle\Str;

/**
 */
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

    /**
     * @var AttributeCode
     */
    private AttributeCode $attributeCode;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(CategoryId::class);
        $this->code = $this->createMock(CategoryCode::class);
        $this->name = $this->createMock(TranslatableString::class);
        $this->attributeCode = $this->createMock(AttributeCode::class);
        $this->attributeCode->method('getValue')->willReturn('aaa');
        $this->attributes = [$this->attributeCode->getValue() => $this->createMock(ValueInterface::class)];
    }

    /**
     */
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
        $name = new TranslatableString(['en' => 'test']);

        $entity = $this->getClass();

        $this->assertEquals($this->id, $entity->getId());
        $this->assertEquals($this->name, $entity->getName());
        $this->assertEquals($this->code, $entity->getCode());
        $this->assertEquals($this->attributes, $entity->getAttributes());

        $this->assertTrue($entity->hasAttribute($this->attributeCode));
        $this->assertEquals(reset($this->attributes), $entity->getAttribute($this->attributeCode));
        $entity->addAttribute($attributeCode, $attributeValue1);
        $this->assertTrue($entity->hasAttribute($attributeCode));
        $this->assertEquals($entity->getAttribute($attributeCode), $attributeValue1);
        $entity->changeAttribute($attributeCode, $attributeValue2);
        $this->assertEquals($entity->getAttribute($attributeCode), $attributeValue2);
        $this->assertArrayHasKey('aaa', ($entity->getAttributes()));
        $this->assertArrayHasKey('bbb', ($entity->getAttributes()));
        $entity->removeAttribute($attributeCode);
        $this->assertFalse($entity->hasAttribute($attributeCode));

        $entity->changeName($name);
        $this->assertEquals($name, $entity->getName());
    }

    /**
     */
    public function testAttributeNotFound(): void
    {
        $entity = $this->getClass();

        $this->expectException(\RuntimeException::class);
        $attributeCode = $this->createMock(AttributeCode::class);
        $entity->getAttribute($attributeCode);
    }

    /**
     */
    public function testAttributeAlreadyExist(): void
    {
        $entity = $this->getClass();

        $this->expectException(\RuntimeException::class);
        $value = $this->createMock(ValueInterface::class);
        $entity->addAttribute($this->attributeCode, $value);
    }

    /**
     */
    public function testChangingNotExistingAttribute(): void
    {
        $entity = $this->getClass();

        $this->expectException(\RuntimeException::class);
        $attributeCode = $this->createMock(AttributeCode::class);
        $value = $this->createMock(ValueInterface::class);
        $entity->changeAttribute($attributeCode, $value);
    }

    /**
     */
    public function testRemovingNotExistingAttribute(): void
    {
        $entity = $this->getClass();

        $this->expectException(\RuntimeException::class);
        $attributeCode = $this->createMock(AttributeCode::class);
        $entity->removeAttribute($attributeCode);
    }

    /**
     * @return AbstractCategory
     */
    private function getClass()
    {
        return new class(
            $this->id,
            $this->code,
            $this->name,
            $this->attributes,
        ) extends AbstractCategory {
            /**
             * @return string
             */
            public function getType(): string
            {
                return 'TYPE';
            }
        };
    }
}
