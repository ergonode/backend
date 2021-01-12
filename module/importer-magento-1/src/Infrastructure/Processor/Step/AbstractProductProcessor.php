<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;

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
     * @var AbstractAttribute[] $attributes
     *
     * @return TranslatableString[]
     */
    protected function getAttributes(
        Magento1CsvSource $source,
        ProductModel $product,
        array $attributes
    ): array {
        $result = [];
        $default = $product->get('default');

        foreach ($default as $field => $value) {
            $translation = [];
            $attribute = $this->getAttribute($field, $attributes);
            if ($attribute) {
                $type = $attribute->getType();
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

    /**
     * @param AbstractAttribute[] $attributes
     */
    private function getAttribute(string $code, array $attributes): ?AbstractAttribute
    {
        foreach ($attributes as $attribute) {
            if ($code === $attribute->getCode()->getValue()) {
                return $attribute;
            }
        }

        return null;
    }
}
