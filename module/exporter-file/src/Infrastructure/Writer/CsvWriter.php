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
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;

/**
 */
class CsvWriter implements WriterInterface
{
    public const TYPE = 'csv';

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
            foreach ($attributes as $attribute) {
                $code = new AttributeCode($attribute);
                $record[$attribute] = null;
                if ($product->hasAttribute($code)) {
                    $value = $product->getAttribute($code);
                    if ($value instanceof TranslatableStringValue) {
                        $record[$attribute] = $value->getValue()->get($language);
                    }
                    if ($value instanceof StringCollectionValue) {
                        $record[$attribute] = implode(',', $value->getValue());
                    }
                }
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
}
