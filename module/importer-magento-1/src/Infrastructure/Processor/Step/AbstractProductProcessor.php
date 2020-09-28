<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
abstract class AbstractProductProcessor
{
    /**
     * @param Transformer       $transformer
     * @param Magento1CsvSource $source
     * @param ProductModel      $product
     *
     * @return Record
     */
    protected function getRecord(Transformer $transformer, Magento1CsvSource $source, ProductModel $product): Record
    {
        $default = $product->get('default');

        $record = new Record();
        $record->set('sku', $product->getSku());
        $record->set('esa_template', $product->getTemplate());

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

                foreach ($translation as $language => $version) {
                    $record->setAttribute($field, $version, new Language($language));
                }
            }

            if (null !== $value && '' !== $value && $transformer->hasField($field)) {
                $record->set($field, $value);
            }
        }

        return $record;
    }

    /**
     * @param string      $type
     * @param string|null $value
     *
     * @return string|null
     */
    protected function format(string $type, ?string $value): ?string
    {
        if ($value && ImageAttribute::TYPE === $type) {
            $value = pathinfo($value, PATHINFO_FILENAME);
            if ('no_selection' === $value) {
                $value = null;
            }
        }

        return $value;
    }
}
