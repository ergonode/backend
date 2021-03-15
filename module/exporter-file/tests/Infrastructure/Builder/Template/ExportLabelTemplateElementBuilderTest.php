<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Builder\Template;

use PHPUnit\Framework\TestCase;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\Entity\Element\UiTemplateElement;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Infrastructure\Builder\Template\ExportLabelTemplateElementBuilder;

class ExportLabelTemplateElementBuilderTest extends TestCase
{
    private TemplateElementInterface $element;

    protected function setUp(): void
    {
        $this->element = $this->createMock(UiTemplateElement::class);
    }

    public function testBuild(): void
    {
        $label = 'Label';
        $data = new ExportLineData();
        $language  = new Language('pl_PL');
        $this->element->expects(self::once())->method('getLabel')->willReturn($label);
        $builder = new ExportLabelTemplateElementBuilder();
        $builder->build($this->element, $data, $language);
        $result = $data->getValues();
        self::assertCount(1, $result);
        self::assertArrayHasKey('label', $result);
        self::assertSame($label, $result['label']);
    }
}
