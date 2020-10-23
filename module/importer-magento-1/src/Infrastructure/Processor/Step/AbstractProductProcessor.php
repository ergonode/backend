<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Category\Domain\ValueObject\CategoryCode;

abstract class AbstractProductProcessor
{
    /**
     * @return CategoryCode[]
     */
    protected function getCategories(ProductModel $product): array
    {
        $result = [];

        $default = $product->get('default');
        if ($categories = $default['esa_categories'] ?? null) {
            foreach (explode(',', $categories) as $category) {
                $result[] = new CategoryCode($category);
            }
        }

        return $result;
    }

    /**
     * @return string[]
     */
    protected function getAttributes(
        Transformer $transformer,
        Magento1CsvSource $source,
        ProductModel $product
    ): array {
        $result = [];
        $default = $product->get('default');

        foreach ($default as $field => $value) {
            $translation = [];
            if ($transformer->hasAttribute($field)) {
                $type = $transformer->getAttributeType($field);
                $value = $this->format($type, $value);
                if ($value) {
                    $translation[$source->getDefaultLanguage()->getCode()] = $value;
                }

                foreach ($source->getLanguages() as $key => $language) {
                    if ($product->has($key)) {
                        $translatedVer = $product->get($key);
                        if (array_key_exists($field, $translatedVer) && null !== $translatedVer[$field]) {
                            $code = $language->getCode();
                            $translation[$code] = $this->format($type, $translatedVer[$field]);
                        }
                    }
                }

                $result[$field] = new TranslatableString($translation);
            }
        }

        return $result;
    }

    protected function format(
        string $type,
        ?string $value
    ): ?string {
        if ($value && ImageAttribute::TYPE === $type) {
            $value = pathinfo($value, PATHINFO_FILENAME);
            if ('no_selection' === $value) {
                $value = null;
            }
        }

        return $value;
    }
}
