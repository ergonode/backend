<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Entity\Attribute;

use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use PHPUnit\Framework\TestCase;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;

class ProductRelationAttributeTest extends TestCase
{
    private AttributeId $id;
    private AttributeCode $code;
    private TranslatableString $label;
    private TranslatableString $hint;
    private TranslatableString $placeholder;
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

    public function testAttributeCreation(): void
    {
        $attribute = new ProductRelationAttribute(
            $this->id,
            $this->code,
            $this->label,
            $this->placeholder,
            $this->hint,
            $this->scope
        );
        self::assertEquals($this->id, $attribute->getId());
        self::assertEquals($this->code, $attribute->getCode());
        self::assertEquals($this->label, $attribute->getLabel());
        self::assertEquals($this->hint, $attribute->getHint());
        self::assertEquals($this->placeholder, $attribute->getPlaceholder());
        self::assertEquals($this->scope, $attribute->getScope());
    }
}
