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
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportProductBuilderInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractTextAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractTextareaAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractPriceAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractNumericAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractUnitAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractDateAttribute;

class ExportProductSimpleAttributeBuilder implements ExportProductBuilderInterface
{
    public const TYPES = [
        AbstractTextAttribute::TYPE,
        AbstractTextareaAttribute::TYPE,
        AbstractNumericAttribute::TYPE,
        AbstractPriceAttribute::TYPE,
        AbstractUnitAttribute::TYPE,
        AbstractDateAttribute::TYPE,
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
                    if ($attribute instanceof AbstractOptionAttribute && $calculatedValue) {
                        $calculatedValue = $this->resolveOptionKey($calculatedValue, $attribute->getCode());
                    }
                    if (is_array($calculatedValue)) {
                        $calculatedValue = implode(',', $calculatedValue);
                    }
                    $result->set($code->getValue(), $calculatedValue);
                }
            }
        }
    }

    /**
     * @param string|string[] $value
     *
     * @return string|string[]
     */
    private function resolveOptionKey($value, AttributeCode $code)
    {
        if (is_string($value)) {
            return $this->findKey($value, $code)->getValue();
        }

        return array_map(
            fn(string $id) => $this->findKey($id, $code)->getValue(),
            $value,
        );
    }

    private function findKey(string $value, AttributeCode $code): OptionKey
    {
        $optionKey = $this->optionQuery->findKey(new AggregateId($value));
        if (!$optionKey) {
            throw new \RuntimeException("There's no option [$value] for '{$code->getValue()}' attribute.");
        }

        return $optionKey;
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
