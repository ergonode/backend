<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Option;

use Ergonode\ExporterFile\Infrastructure\Builder\ExportOptionBuilderInterface;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Webmozart\Assert\Assert;

class ExportOptionAttributeBuilder implements ExportOptionBuilderInterface
{
    private AttributeQueryInterface $attributeQuery;

    public function __construct(AttributeQueryInterface $attributeQuery)
    {
        $this->attributeQuery = $attributeQuery;
    }

    public function header(): array
    {
        return ['_attribute'];
    }

    public function build(AbstractOption $option, ExportLineData $line, Language $language): void
    {
        $line->set('_attribute', $this->getAttribute($option)->getValue());
    }

    private function getAttribute(AbstractOption $option): AttributeCode
    {
        $attributeId = $option->getAttributeId();
        $code = $this->attributeQuery->findAttributeCodeById($attributeId);
        Assert::notNull($code, sprintf('Can\'t find code of attribute "%s"', $attributeId->getValue()));

        return $code;
    }
}
