<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Entity;

use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\SharedKernel\Domain\AggregateId;

class AbstractOptionTest extends TestCase
{
    /**
     * @var AggregateId|MockObject
     */
    private AggregateId $id;

    /**
     * @var AttributeId|MockObject
     */
    private AttributeId $attributeId;

    /**
     * @var OptionKey|MockObject
     */
    private OptionKey $code;

    /**
     * @var TranslatableString|MockObject
     */
    private TranslatableString $label;


    public function setUp(): void
    {
        $this->id = $this->createMock(AggregateId::class);
        $this->attributeId = $this->createMock(AttributeId::class);
        $this->code = $this->createMock(OptionKey::class);
        $this->label = $this->createMock(TranslatableString::class);
    }

    /**
     * @throws \Exception
     */
    public function testCreation(): void
    {
        $option = $this->getClass();

        $this->assertEquals($this->id, $option->getId());
        $this->assertEquals($this->attributeId, $option->getAttributeId());
        $this->assertEquals($this->code, $option->getCode());
        $this->assertEquals($this->label, $option->getLabel());
    }

    /**
     * @throws \Exception
     */
    public function testLabelManipulationCreation(): void
    {
        $label = $this->createMock(TranslatableString::class);
        $label->method('isEqual')->willReturn(false);

        $option = $this->getClass();
        $option->changeLabel($label);
        $this->assertSame($label, $option->getLabel());
        $this->assertNotSame($this->label, $option->getLabel());
    }

    /**
     * @throws \Exception
     */
    public function testCodeManipulationCreation(): void
    {
        $code = $this->createMock(OptionKey::class);
        $code->method('isEqual')->willReturn(false);

        $option = $this->getClass();
        $option->changeCode($code);
        $this->assertSame($code, $option->getCode());
        $this->assertNotSame($this->code, $option->getCode());
    }

    private function getClass(): AbstractOption
    {
        return  new class(
            $this->id,
            $this->attributeId,
            $this->code,
            $this->label,
        ) extends AbstractOption {

        };
    }
}
