<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Entity;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AbstractAttributeTest extends TestCase
{
    /**
     * @var AttributeId|MockObject
     */
    private AttributeId $id;

    /**
     * @var AttributeCode|MockObject
     */
    private AttributeCode $code;

    /**
     * @var TranslatableString|MockObject
     */
    private TranslatableString $translation;

    /**
     * @var AttributeScope|MockObject
     */
    private AttributeScope $scope;

    /**
     * @var string[]
     */
    private array $parameters;

    public function setUp(): void
    {
        $this->id = $this->createMock(AttributeId::class);
        $this->code = $this->createMock(AttributeCode::class);
        $this->translation = $this->createMock(TranslatableString::class);
        $this->scope = $this->createMock(AttributeScope::class);
        $this->parameters = ['paramater1' => 'value1'];
    }

    /**
     * @throws \Exception
     */
    public function testAttributeCreation(): void
    {
        $attribute = $this->getClass();

        $this->assertEquals($this->id, $attribute->getId());
        $this->assertEquals($this->code, $attribute->getCode());
        $this->assertEquals($this->translation, $attribute->getLabel());
        $this->assertEquals($this->translation, $attribute->getHint());
        $this->assertEquals($this->translation, $attribute->getPlaceholder());
        $this->assertEquals($this->scope, $attribute->getScope());
        $this->assertEquals($this->parameters, $attribute->getParameters());
    }

    /**
     * @throws \Exception
     */
    public function testLabelManipulation(): void
    {
        $translation = $this->createMock(TranslatableString::class);
        $translation->method('isEqual')->willReturn(false);
        $attribute = $this->getClass();
        $attribute->changeLabel($translation);
        $this->assertNotSame($this->translation, $attribute->getLabel());
        $this->assertSame($translation, $attribute->getLabel());
    }

    /**
     * @throws \Exception
     */
    public function testPlaceholderManipulation(): void
    {
        $translation = $this->createMock(TranslatableString::class);
        $translation->method('isEqual')->willReturn(false);
        $attribute = $this->getClass();
        $attribute->changePlaceholder($translation);
        $this->assertNotSame($this->translation, $attribute->getPlaceholder());
        $this->assertSame($translation, $attribute->getPlaceholder());
    }

    /**
     * @throws \Exception
     */
    public function testHintManipulation(): void
    {
        $translation = $this->createMock(TranslatableString::class);
        $translation->method('isEqual')->willReturn(false);
        $attribute = $this->getClass();
        $attribute->changeHint($translation);
        $this->assertNotSame($this->translation, $attribute->getHint());
        $this->assertSame($translation, $attribute->getHint());
    }

    /**
     * @throws \Exception
     */
    public function testGroupManipulation(): void
    {
        $groupId = AttributeGroupId::generate();

        $attribute = $this->getClass();
        $attribute->addGroup($groupId);
        $this->assertTrue($attribute->inGroup($groupId));
        $this->assertEquals([$groupId], $attribute->getGroups());
        $attribute->removeGroup($groupId);
        $this->assertFalse($attribute->inGroup($groupId));
        $this->assertEquals([], $attribute->getGroups());
    }

    private function getClass(): AbstractAttribute
    {
        return  new class(
            $this->id,
            $this->code,
            $this->translation,
            $this->translation,
            $this->translation,
            $this->scope,
            $this->parameters,
        ) extends AbstractAttribute {
            public function getType(): string
            {
                return 'TYPE';
            }
        };
    }
}
