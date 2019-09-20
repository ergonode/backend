<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryTest extends TestCase
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
    private $attributes;

    /**
     * @var AttributeCode
     */
    private $attributeCode;

    /**
     */
    protected function setUp()
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
        /** @var ValueInterface|MockObject $attribute */
        $attribute = $this->createMock(ValueInterface::class);

        /** @var TranslatableString|MockObject $name */
        $name = $this->createMock(TranslatableString::class);

        $entity = new Category($this->id, $this->code, $this->name, $this->attributes);
        $this->assertEquals($this->id, $entity->getId());
        $this->assertEquals($this->name, $entity->getName());
        $this->assertEquals($this->code, $entity->getCode());
        $this->assertEquals($this->attributes, $entity->getAttributes());

        $this->assertTrue($entity->hasAttribute($this->attributeCode));
        $this->assertEquals(reset($this->attributes), $entity->getAttribute($this->attributeCode));
        $entity->addAttribute($attributeCode, $attribute);
        $this->assertTrue($entity->hasAttribute($attributeCode));

        $entity->changeName($name);
        $this->assertEquals($name, $entity->getName());
    }
}
