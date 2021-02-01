<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Tests\Domain\Calculator\Strategy;

use Ergonode\Completeness\Domain\Calculator\Strategy\UiTemplateElementCompletenessStrategy;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Designer\Domain\Entity\Element\UiTemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class UiTemplateElementCompletenessStrategyTest extends TestCase
{
    public function testSupport(): void
    {
        $strategy = new UiTemplateElementCompletenessStrategy();
        $this::assertTrue($strategy->supports(UiTemplateElement::TYPE));
        $this::assertFalse($strategy->supports('Any other variant'));
    }

    public function testGetElementCompleteness(): void
    {
        $product = $this->createMock(AbstractProduct::class);
        $language = $this->createMock(Language::class);
        $property = $this->createMock(TemplateElementInterface::class);

        $strategy = new UiTemplateElementCompletenessStrategy();
        $result = $strategy->getElementCompleteness($product, $language, $property);
        $this->assertNull($result);
    }
}
