<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Tests\Domain\Calculator;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculator;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use PHPUnit\Framework\TestCase;
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculatorLine;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Completeness\Domain\Calculator\AttributeTemplateElementCompletenessCalculator;

class CompletenessCalculatorTest extends TestCase
{
    public function testCalculation(): void
    {
        $model = $this->createMock(CompletenessCalculatorLine::class);
        $element = $this->createMock(TemplateElementInterface::class);
        $calculator = $this->createMock(AttributeTemplateElementCompletenessCalculator::class);
        $calculator->method('calculate')->willReturn($model);

        $product = $this->createMock(AbstractProduct::class);
        $template = $this->createMock(Template::class);
        $template->method('getElements')->willReturn(new ArrayCollection([$element]));
        $language = $this->createMock(Language::class);
        $calculator = new CompletenessCalculator($calculator);
        $result = $calculator->calculate($product, $template, $language);
        self::assertNotNull($result);
    }
}
