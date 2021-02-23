<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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

class ProductAttributeBuilder implements ExportProductBuilderInterface
{
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
        return $this->attributeQuery->getAllAttributeCodes();
    }

    public function build(AbstractProduct $product, ExportLineData $result, Language $language): void
    {
        foreach ($product->getAttributes() as $code => $value) {
            $code = new AttributeCode($code);
            $attribute = $this->getAttribute($code);
            $calculatedValue = $this->calculator->calculate($attribute, $value, $language);
            if ($attribute instanceof AbstractOptionAttribute && $calculatedValue) {
                $calculatedValue = $this->resolveOptionKey($calculatedValue, $attribute->getCode());
            }
            if (is_array($calculatedValue)) {
                $calculatedValue = implode(',', $calculatedValue);
            }

            $result->set($code->getValue(), $calculatedValue);
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
