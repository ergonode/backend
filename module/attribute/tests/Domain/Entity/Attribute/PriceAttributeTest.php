<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Entity\Attribute;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Money\Currency;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class PriceAttributeTest extends TestCase
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
     * @var Currency|MockObject
     */
    private $currency;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(AttributeId::class);
        $this->code = $this->createMock(AttributeCode::class);
        $this->label = $this->createMock(TranslatableString::class);
        $this->hint = $this->createMock(TranslatableString::class);
        $this->placeholder = $this->createMock(TranslatableString::class);
        $this->currency = new Currency('CURRENCY');
    }

    /**
     * @throws \Exception
     */
    public function testAttributeCreation(): void
    {
        $attribute = new PriceAttribute(
            $this->id,
            $this->code,
            $this->label,
            $this->placeholder,
            $this->hint,
            $this->currency
        );
        $this->assertEquals($this->currency, $attribute->getCurrency());
        $this->assertEquals($this->id, $attribute->getId());
        $this->assertEquals($this->code, $attribute->getCode());
        $this->assertEquals($this->label, $attribute->getLabel());
        $this->assertEquals($this->hint, $attribute->getHint());
        $this->assertEquals($this->placeholder, $attribute->getPlaceholder());
    }

    /**
     * @throws \Exception
     */
    public function testAttributeCurrencyChange(): void
    {
        $currency = new Currency('NEW');
        $attribute = new PriceAttribute(
            $this->id,
            $this->code,
            $this->label,
            $this->placeholder,
            $this->hint,
            $this->currency
        );
        $attribute->changeCurrency($currency);
        $this->assertNotEquals($this->currency, $attribute->getCurrency());
        $this->assertEquals($currency, $attribute->getCurrency());
    }
}
