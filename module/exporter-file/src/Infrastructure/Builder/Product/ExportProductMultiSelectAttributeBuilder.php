<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Product;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Core\Domain\ValueObject\Language;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportProductBuilderInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Channel\Infrastructure\Exception\ExportException;

class ExportProductMultiSelectAttributeBuilder implements ExportProductBuilderInterface
{
    public const TYPES = [
        MultiSelectAttribute::TYPE,
    ];

    private AttributeQueryInterface $attributeQuery;

    private AttributeRepositoryInterface $attributeRepository;

    private OptionQueryInterface $optionQuery;

    private TranslationInheritanceCalculator $calculator;

    public function __construct(
        AttributeQueryInterface $attributeQuery,
        AttributeRepositoryInterface $attributeRepository,
        OptionQueryInterface $optionQuery,
        TranslationInheritanceCalculator $calculator
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->attributeRepository = $attributeRepository;
        $this->optionQuery = $optionQuery;
        $this->calculator = $calculator;
    }

    public function header(): array
    {
        return $this->attributeQuery->getAttributeCodes(self::TYPES, false);
    }

    public function build(AbstractProduct $product, ExportLineData $result, Language $language): void
    {
        foreach ($this->attributeQuery->getAttributeCodes(self::TYPES, false) as $attributeCode) {
            $result->set($attributeCode);
            $code = new AttributeCode($attributeCode);
            if ($product->hasAttribute($code)) {
                $value = $product->getAttribute($code);
                $attribute = $this->getAttribute($code);
                $calculatedValue = $this->calculator->calculate($attribute->getScope(), $value, $language);
                if (null !== $calculatedValue) {
                    if (!is_array($calculatedValue)) {
                        throw new ExportException(
                            sprintf(
                                'Can\'t calculate value for attribute "%s" in product "%s"',
                                $attributeCode,
                                $product->getSku()->getValue()
                            )
                        );
                    }
                    $result->set($code->getValue(), $this->findKey($calculatedValue, $code));
                }
            }
        }
    }

    private function findKey(array $value, AttributeCode $code): string
    {
        $result = [];
        foreach ($value as $element) {
            $optionKey = $this->optionQuery->findKey(new AggregateId($element));
            if (!$optionKey) {
                throw new \RuntimeException("There's no option [$element] for '{$code->getValue()}' attribute.");
            }
            $result[] = $optionKey->getValue();
        }

        return implode(',', $result);
    }

    private function getAttribute(AttributeCode $code): AbstractAttribute
    {
        $attributeId = $this->attributeQuery->findAttributeIdByCode($code);
        Assert::notNull($attributeId);
        $attribute = $this->attributeRepository->load($attributeId);
        Assert::isInstanceOf($attribute, AbstractAttribute::class);

        return $attribute;
    }
}
