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
class XmlWriter implements WriterInterface
{
    public const TYPE = 'xml';

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
     * @param array $headers
     *
     * @return string[]
     */
    public function start(array $headers): array
    {
        return [
            $this->add('<?xml version="1.0" encoding="UTF-8"?>'),
            $this->add('<products>', 2),
        ];
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
        $result[] = $this->add(sprintf('<product sku="%s">', $product->getSku()->getValue()), 4);
        foreach ($languages as $language) {
            $result[] = $this->add(sprintf('<language code="%s">', $language->getCode()), 6);
            foreach ($attributes as $code) {
                $attributeCode = new AttributeCode($code);
                if ($product->hasAttribute($attributeCode)) {
                    $value = $this->getValue($product, $attributeCode, $language);
                    $result[] = $this->add(sprintf('<attribute code="%s" value="%s" />', $code, $value), 8);
                }
            }
            $result[] = $this->add('</language>', 6);
        }
        $result[] = $this->add('</product>', 4);

        return $result;
    }

    /**
     * @param array $attributes
     *
     * @return string[]
     */
    public function end(
        array $attributes
    ): array {
        return [
            $this->add('</products>', 2),
        ];
    }

    /**
     * @param string $line
     * @param int    $ident
     *
     * @return string
     */
    private function add(string $line, int $ident = 0): string
    {
        return sprintf('%s%s%s', str_repeat(' ', $ident), $line, PHP_EOL);
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
            $value = $product->getAttribute($code);
            if ($value instanceof TranslatableStringValue) {
                return $value->getValue()->get($language);
            }
            if ($value instanceof StringCollectionValue) {
                return implode(',', $value->getValue());
            }
        }

        return null;
    }
}
