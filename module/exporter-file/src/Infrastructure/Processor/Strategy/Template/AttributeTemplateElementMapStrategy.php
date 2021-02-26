<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Strategy\Template;

use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\ExporterFile\Infrastructure\Processor\Strategy\TemplateElementMapInterface;

class AttributeTemplateElementMapStrategy implements TemplateElementMapInterface
{
    private AttributeQueryInterface $attributeQuery;

    public function __construct(AttributeQueryInterface $attributeQuery)
    {
        $this->attributeQuery = $attributeQuery;
    }

    public function support(TemplateElementInterface $element): bool
    {
        return AttributeTemplateElement::TYPE === $element->getType();
    }

    /**
     * @param AttributeTemplateElement $element
     */
    public function map(TemplateElementInterface $element): array
    {
        Assert::isInstanceOf($element, AttributeTemplateElement::class);

        $attributeId = $element->getAttributeId();

        $attributeCode = $this->attributeQuery->findAttributeCodeById($attributeId);

        Assert::isInstanceOf($attributeCode, AttributeCode::class);

        return [
            'attribute' => $attributeCode->getValue(),
            'require' => $element->isRequired(),
        ];
    }
}
