<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\ProductModel;

class ErgonodeProductReader extends AbstractErgonodeReader
{
    public function read(): ?ProductModel
    {
        $item = null;
        $attributes = $this->prepareAttributes();

        while ($this->records->valid()) {
            $record = $this->records->current();

            if (null === $item) {
                $item = new ProductModel(
                    $record['_id'],
                    $record['_sku'],
                    $record['_type'],
                    $record['_template']
                );
            } elseif ($item->getId() !== $record['_id']) {
                break;
            }

            foreach ($attributes as $attribute) {
                $item->addAttribute($attribute, $record['_language'], $record[$attribute]);
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
