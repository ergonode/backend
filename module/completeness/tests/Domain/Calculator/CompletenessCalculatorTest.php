<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Tests\Domain\Calculator;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculator;
use Ergonode\Completeness\Domain\Calculator\Strategy\TemplateElementCompletenessStrategyInterface;
use Ergonode\Completeness\Domain\Provider\TemplateElementCompletenessStrategyProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculatorLine;

/**
 */
class CompletenessCalculatorTest extends TestCase
{
    /**
     */
    public function testCalculation(): void
    {
        $model = $this->createMock(CompletenessCalculatorLine::class);
        $element = $this->createMock(TemplateElement::class);
        $strategy = $this->createMock(TemplateElementCompletenessStrategyInterface::class);
        $strategy->method('getElementCompleteness')->willReturn($model);

        /** @var TemplateElementCompletenessStrategyProvider|MockObject $provider */
        $provider = $this->createMock(TemplateElementCompletenessStrategyProvider::class);
        $provider->method('provide')->willReturn($strategy);
        $draft = $this->createMock(ProductDraft::class);
        $template = $this->createMock(Template::class);
        $template->method('getElements')->willReturn(new ArrayCollection([$element]));
        $language = $this->createMock(Language::class);
        $calculator = new CompletenessCalculator($provider);
        $result = $calculator->calculate($draft, $template, $language);
        self::assertNotNull($result);
    }
}
