<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Tests\Domain\Calculator;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculator;
use Ergonode\Completeness\Domain\Provider\TemplateElementCompletenessStrategyProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CompletenessCalculatorTest extends TestCase
{
    /**
     */
    public function testCalculation(): void
    {
        /** @var TemplateElementCompletenessStrategyProvider|MockObject $provider */
        $provider = $this->createMock(TemplateElementCompletenessStrategyProvider::class);
        $draft = $this->createMock(ProductDraft::class);
        $template = $this->createMock(Template::class);
        $template->method('getElements')->willReturn(new ArrayCollection());
        $language = $this->createMock(Language::class);
        $calculator = new CompletenessCalculator($provider);
        $result = $calculator->calculate($draft, $template, $language);
        $this->assertNotNull($result);
    }
}
