<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor\Strategy\Template;

use PHPUnit\Framework\TestCase;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;
use Ergonode\ExporterFile\Infrastructure\Processor\Strategy\Template\AttributeTemplateElementMapStrategy;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

class AttributeTemplateElementMapStrategyTest extends TestCase
{
    private TemplateElementInterface $element;

    private AttributeQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(AttributeQueryInterface::class);
        $this->element = $this->createMock(AttributeTemplateElement::class);
    }

    public function testSupportStrategy(): void
    {
        $this->element->expects(self::once())->method('getType')
            ->willReturn(AttributeTemplateElement::TYPE);

        $mapper = new AttributeTemplateElementMapStrategy($this->query);
        self::assertTrue($mapper->support($this->element));
    }

    public function testNotSupportStrategy(): void
    {
        $this->element->expects(self::once())->method('getType')->willReturn('Any not support type');

        $mapper = new AttributeTemplateElementMapStrategy($this->query);
        self::assertFalse($mapper->support($this->element));
    }

    public function testMapping(): void
    {
        $require = true;
        $code = 'code';

        $this->element->expects(self::once())->method('isRequired')->willReturn($require);
        $this->query->expects(self::once())->method('findAttributeCodeById')->willReturn(new AttributeCode($code));

        $mapper = new AttributeTemplateElementMapStrategy($this->query);
        $result = $mapper->map($this->element);
        self::assertCount(2, $result);
        self::assertArrayHasKey('require', $result);
        self::assertArrayHasKey('attribute', $result);
        self::assertSame($code, $result['attribute']);
        self::assertSame($require, $result['require']);
    }
}
