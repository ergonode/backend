<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Option;

use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
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

    private OptionQueryInterface $optionQuery;

    public function __construct(AttributeQueryInterface $attributeQuery, OptionQueryInterface $optionQuery)
    {
        $this->attributeQuery = $attributeQuery;
        $this->optionQuery = $optionQuery;
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
        $attributeId = $this->optionQuery->getAttributeIdByOptionId($option->getId());
        Assert::notNull($attributeId, sprintf('Can\'t find attribute for the option "%s"', $option->getCode()));
        $code = $this->attributeQuery->findAttributeCodeById($attributeId);
        Assert::notNull($code, sprintf('Can\'t find code of attribute "%s"', $attributeId->getValue()));

        return $code;
    }
}
