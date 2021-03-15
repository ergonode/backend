<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\ProductModel;

class ErgonodeProductReader extends AbstractErgonodeReader
{
    private const KEYS = [
        '_sku',
        '_type',
        '_template',
        '_language',
        '_categories',
    ];

    public function read(): ?ProductModel
    {
        $item = null;
        $attributes = $this->prepareAttributes();

        while ($this->records->valid()) {
            $record = $this->records->current();

            if (null === $item) {
                $item = new ProductModel(
                    $record['_sku'],
                    $record['_type'],
                    $record['_template']
                );
            } elseif ($item->getSku() !== $record['_sku']) {
                break;
            }

            if (!empty($record['_categories'])) {
                $categoryCodes = explode(',', $record['_categories']);
                foreach ($categoryCodes as $code) {
                    $item->addCategory($code);
                }
            }

            foreach ($attributes as $attribute) {
                $item->addAttribute($attribute, $record['_language'], $record[$attribute]);
            }

            foreach ($record as $key => $value) {
                if ('' !== $value && !array_key_exists($key, self::KEYS) && !array_key_exists($key, $attributes)) {
                    $item->addParameter($key, $value);
                }
            }

            $this->records->next();
        }

        return $item;
    }

    private function prepareAttributes(): array
    {
        return array_filter($this->headers, static function ($item) {
            return '_' !== $item[0];
        });
    }
}
