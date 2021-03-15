<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NumericAttributeTest extends TestCase
{
    /**
     * @var AttributeId|MockObject
     */
    private $id;

    /**
     * @var AttributeCode|MockObject
     */
    private $code;

    /**
     * @var TranslatableString|MockObject
     */
    private $label;

    /**
     * @var TranslatableString|MockObject
     */
    private $hint;

    /**
     * @var TranslatableString|MockObject
     */
    private $placeholder;

    /**
     * @var AttributeScope|MockObject
     */
    private AttributeScope $scope;

    protected function setUp(): void
    {
        $this->id = $this->createMock(AttributeId::class);
        $this->code = $this->createMock(AttributeCode::class);
        $this->label = $this->createMock(TranslatableString::class);
        $this->hint = $this->createMock(TranslatableString::class);
        $this->placeholder = $this->createMock(TranslatableString::class);
        $this->scope = $this->createMock(AttributeScope::class);
    }

    /**
     * @throws \Exception
     */
    public function testAttributeCreation(): void
    {
        $attribute = new NumericAttribute(
            $this->id,
            $this->code,
            $this->label,
            $this->placeholder,
            $this->hint,
            $this->scope
        );
        $this->assertEquals($this->id, $attribute->getId());
        $this->assertEquals($this->code, $attribute->getCode());
        $this->assertEquals($this->label, $attribute->getLabel());
        $this->assertEquals($this->hint, $attribute->getHint());
        $this->assertEquals($this->placeholder, $attribute->getPlaceholder());
        $this->assertEquals($this->scope, $attribute->getScope());
    }
}
