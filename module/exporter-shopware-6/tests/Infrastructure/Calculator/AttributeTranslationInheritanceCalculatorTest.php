<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Calculator;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeTranslationInheritanceCalculatorTest extends TestCase
{
    /**
     * @var LanguageQueryInterface|MockObject
     */
    private LanguageQueryInterface $languageQuery;

    /**
     */
    public function setUp(): void
    {
        $this->languageQuery = $this->createMock(LanguageQueryInterface::class);
        $this->languageQuery->method('getRootLanguage')
            ->willReturn(new Language('en'));
    }

    /**
     */
    public function testCalculate()
    {
        $calculator = new AttributeTranslationInheritanceCalculator($this->languageQuery);

        $attribute = $this->createMock(AbstractAttribute::class);
        $value = new TranslatableStringValue(new TranslatableString(['en' => 'TEST en', 'pl' => 'TEST pl']));

        $language = new Language('pl');

        $newValue = $calculator->calculate($attribute, $value, $language);
        $this->assertIsString($newValue);
    }

    /**
     */
    public function testCalculateGlobal()
    {
        $calculator = new AttributeTranslationInheritanceCalculator($this->languageQuery);

        $attribute = $this->createMock(AbstractAttribute::class);
        $attribute->method('getScope')->willReturn(new AttributeScope(AttributeScope::GLOBAL));
        $value = new TranslatableStringValue(new TranslatableString(['en' => 'TEST en', 'pl' => 'TEST pl']));

        $language = new Language('pl');

        $newValue = $calculator->calculate($attribute, $value, $language);
        $this->assertIsString($newValue);
    }

    /**
     */
    public function testCalculateNoTranslate()
    {
        $calculator = new AttributeTranslationInheritanceCalculator($this->languageQuery);

        $attribute = $this->createMock(AbstractAttribute::class);
        $value = new StringValue('TEST VALUE');

        $language = new Language('pl');

        $newValue = $calculator->calculate($attribute, $value, $language);
        $this->assertIsString($newValue);
    }
}
