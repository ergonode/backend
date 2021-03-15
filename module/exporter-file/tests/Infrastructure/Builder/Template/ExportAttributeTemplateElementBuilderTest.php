<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Builder\Template;

use PHPUnit\Framework\TestCase;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\ExporterFile\Infrastructure\Builder\Template\ExportAttributeTemplateElementBuilder;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;

class ExportAttributeTemplateElementBuilderTest extends TestCase
{
    private TemplateElementInterface $element;

    private AttributeQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(AttributeQueryInterface::class);
        $this->element = $this->createMock(AttributeTemplateElement::class);
    }

    public function testBuilder(): void
    {
        $require = true;
        $code = 'code';
        $data = new ExportLineData();
        $language  = new Language('pl_PL');

        $this->element->expects(self::once())->method('isRequired')->willReturn($require);
        $this->query->expects(self::once())->method('findAttributeCodeById')->willReturn(new AttributeCode($code));

        $builder = new ExportAttributeTemplateElementBuilder($this->query);
        $builder->build($this->element, $data, $language);
        $result = $data->getValues();
        self::assertCount(2, $result);
        self::assertArrayHasKey('require', $result);
        self::assertArrayHasKey('attribute', $result);
        self::assertSame($code, $result['attribute']);
        self::assertSame('true', $result['require']);
    }
}
