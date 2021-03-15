<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Template\Strategy;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Importer\Infrastructure\Action\Process\Template\TemplateElementBuilderInterface;

class AttributeTemplateElementBuilderStrategy implements TemplateElementBuilderInterface
{
    private AttributeQueryInterface $attributeQuery;

    public function __construct(AttributeQueryInterface $attributeQuery)
    {
        $this->attributeQuery = $attributeQuery;
    }

    public function supported(string $type): bool
    {
        return AttributeTemplateElement::TYPE === $type;
    }

    public function build(
        Template $template,
        Position $position,
        Size $size,
        array $properties
    ): TemplateElementInterface {

        $attributeCode = $properties['attribute'];
        $required = 'true' === $properties['require'];

        if (!AttributeCode::isValid($attributeCode)) {
            throw new ImportException(
                'attribute {code} for template {name} is invalid',
                [
                    '{code}' => $attributeCode,
                    '{name}' => $template->getName(),
                ]
            );
        }

        $attributeId = $this->attributeQuery->findAttributeIdByCode(new AttributeCode($attributeCode));

        if (!$attributeId) {
            throw new ImportException(
                'can\'t find attribute {code} for template {name}',
                [
                    '{code}' => $attributeCode,
                    '{name}' => $template->getName(),
                ]
            );
        }

        return new AttributeTemplateElement($position, $size, $attributeId, $required);
    }
}
