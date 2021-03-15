<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\CategoryModel;

class ErgonodeCategoryReader extends AbstractErgonodeReader
{
    private const KEYS = [
        '_code',
        '_name',
    ];

    public function read(): ?CategoryModel
    {
        $item = null;

        while ($this->records->valid()) {
            $record = $this->records->current();

            if (null === $item) {
                $item = new CategoryModel($record['_code']);
            } elseif ($item->getCode() !== $record['_code']) {
                break;
            }
            if (!empty($record['_name'])) {
                $item->addTranslation($record['_language'], $record['_name']);
            }

            foreach ($record as $key => $value) {
                if ('' !== $value && !array_key_exists($key, self::KEYS)) {
                    $item->addParameter($key, $value);
                }
            }

            $this->records->next();
        }

        return $item;
    }
}
