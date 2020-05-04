<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Entity;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

/**
 */
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
     * @var AbstractAttribute|MockObject
     */
    private AbstractAttribute $class;

    /**
     * @var bool
     */
    private bool $multilingual;

    // @codingStandardsIgnoreStart
    /**
     */
    public function setUp(): void
    {

        $this->id = $this->createMock(AttributeId::class);
        $this->code = $this->createMock(AttributeCode::class);
        $this->translation = $this->createMock(TranslatableString::class);
        $this->multilingual = true;

        /**
         */
        $this->class = new class(
            $this->id,
            $this->code,
            $this->translation,
            $this->translation,
            $this->translation,
            $this->multilingual,
        ) extends AbstractAttribute {
            /**
             * @return string
             */
            public function getType(): string
            {
                return 'TYPE';
            }
        };
    }
    // @codingStandardsIgnoreEnd

    /**
     * @throws \Exception
     */
    public function testAttributeCreation(): void
    {
        $attribute = $this->class;

        $this->assertEquals($this->id, $attribute->getId());
        $this->assertEquals($this->code, $attribute->getCode());
        $this->assertEquals($this->translation, $attribute->getLabel());
        $this->assertEquals($this->translation, $attribute->getHint());
        $this->assertEquals($this->translation, $attribute->getPlaceholder());
        $this->assertEquals($this->multilingual, $attribute->isMultilingual());
    }

    /**
     * @throws \Exception
     */
    public function testLabelManipulation(): void
    {
        $translation = $this->createMock(TranslatableString::class);
        $translation->method('isEqual')->willReturn(false);
        $attribute = $this->class;
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
        $attribute = $this->class;
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
        $attribute = $this->class;
        $attribute->changeHint($translation);
        $this->assertNotSame($this->translation, $attribute->getHint());
        $this->assertSame($translation, $attribute->getHint());
    }
}
