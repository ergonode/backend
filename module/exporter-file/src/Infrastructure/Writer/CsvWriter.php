<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Writer;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Webmozart\Assert\Assert;

/**
 */
class CsvWriter implements WriterInterface
{
    public const TYPE = 'csv';

    /**
     * @var TranslationInheritanceCalculator
     */
    private TranslationInheritanceCalculator $calculator;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @param TranslationInheritanceCalculator $calculator
     * @param AttributeRepositoryInterface     $repository
     */
    public function __construct(TranslationInheritanceCalculator $calculator, AttributeRepositoryInterface $repository)
    {
        $this->calculator = $calculator;
        $this->repository = $repository;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool
    {
        return self::TYPE === $type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param array $attributes
     *
     * @return string[]
     */
    public function start(array $attributes): array
    {
        $headers = array_merge(['_sku', '_language'], $attributes);

        $result = $this->getLine($headers);

        return [$result];
    }

    /**
     * @param AbstractProduct $product
     * @param Language[]      $languages
     * @param array           $attributes
     *
     * @return array
     */
    public function write(AbstractProduct $product, array $languages, array $attributes): array
    {
        $result = [];

        foreach ($languages as $language) {
            $system = [
                $product->getSku()->getValue(),
                $language->getCode(),
            ];
            $record = [];
            foreach ($attributes as $attributeCode) {
                $record[$attributeCode] = $this->getValue($product, new AttributeCode($attributeCode), $language);
            }
            $data = array_merge($system, $record);
            $result[] = $this->getLine($data);
        }

        return $result;
    }

    /**
     * @param array $attributes
     *
     * @return string[]
     */
    public function end(array $attributes): array
    {
        return [];
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function getLine(array $data): string
    {
        $buffer = fopen('php://temp', 'rb+');

        fputcsv($buffer, $data);

        rewind($buffer);
        $csv = fgets($buffer);
        fclose($buffer);

        return $csv;
    }

    /**
     * @param AbstractProduct $product
     * @param AttributeCode   $code
     * @param Language        $language
     *
     * @return string|null
     */
    private function getValue(AbstractProduct $product, AttributeCode $code, Language $language): ?string
    {
        if ($product->hasAttribute($code)) {
            $attributeId = AttributeId::fromKey($code->getValue());
            $attribute = $this->repository->load($attributeId);
            Assert::notNull($attribute);
            $value = $product->getAttribute($code);

            $result = $this->calculator->calculate($attribute, $value, $language);
            if (is_array($result)) {
                $result = implode(',', $result);
            }

            return $result;
        }

        return null;
    }
}
