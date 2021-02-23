<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Template;

use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\ExporterFile\Infrastructure\Builder\TemplateElementBuilderInterface;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;

class AttributeTemplateElementBuilder implements TemplateElementBuilderInterface
{
    private AttributeQueryInterface $attributeQuery;

    public function __construct(AttributeQueryInterface $attributeQuery)
    {
        $this->attributeQuery = $attributeQuery;
    }

    public function header(): array
    {
        return ['attribute', 'require'];
    }

    public function build(TemplateElementInterface $element, ExportLineData $data): void
    {
        if ($element instanceof AttributeTemplateElement) {
            $attributeId = $element->getAttributeId();
            $attributeCode = $this->attributeQuery->findAttributeCodeById($attributeId);

            Assert::isInstanceOf($attributeCode, AttributeCode::class);

            $data->set('attribute', $attributeCode->getValue());
            $data->set('require', (string) $element->isRequired());
        }
    }
}
