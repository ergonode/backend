<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Tests\Domain\Calculator\Strategy;

use Ergonode\Completeness\Domain\Calculator\Strategy\UiTemplateElementCompletenessStrategy;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\UiTemplateElementProperty;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use PHPUnit\Framework\TestCase;

class UiTemplateElementCompletenessStrategyTest extends TestCase
{
    public function testSupport(): void
    {
        $strategy = new UiTemplateElementCompletenessStrategy();
        $this::assertTrue($strategy->supports(UiTemplateElementProperty::VARIANT));
        $this::assertFalse($strategy->supports('Any other variant'));
    }

    public function testGetElementCompleteness(): void
    {
        $draft = $this->createMock(ProductDraft::class);
        $language = $this->createMock(Language::class);
        $property = $this->createMock(TemplateElementPropertyInterface::class);

        $strategy = new UiTemplateElementCompletenessStrategy();
        $result = $strategy->getElementCompleteness($draft, $language, $property);
        $this->assertNull($result);
    }
}
