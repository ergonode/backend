<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor\Strategy\Template;

use Ergonode\ExporterFile\Infrastructure\Processor\Strategy\Template\LabelTemplateElementMapStrategy;
use PHPUnit\Framework\TestCase;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\Entity\Element\UiTemplateElement;

class LabelTemplateElementMapStrategyTest extends TestCase
{
    private TemplateElementInterface $element;

    protected function setUp(): void
    {
        $this->element = $this->createMock(UiTemplateElement::class);
    }

    public function testSupportStrategy(): void
    {
        $this->element->expects(self::once())->method('getType')->willReturn(UiTemplateElement::TYPE);

        $mapper = new LabelTemplateElementMapStrategy();
        self::assertTrue($mapper->support($this->element));
    }

    public function testNotSupportStrategy(): void
    {
        $this->element->expects(self::once())->method('getType')->willReturn('Any not support type');

        $mapper = new LabelTemplateElementMapStrategy();
        self::assertFalse($mapper->support($this->element));
    }

    public function testMapping(): void
    {
        $label = 'Label';

        $this->element->expects(self::once())->method('getLabel')->willReturn($label);

        $mapper = new LabelTemplateElementMapStrategy();
        $result = $mapper->map($this->element);
        self::assertCount(1, $result);
        self::assertArrayHasKey('label', $result);
        self::assertSame($label, $result['label']);
    }
}
