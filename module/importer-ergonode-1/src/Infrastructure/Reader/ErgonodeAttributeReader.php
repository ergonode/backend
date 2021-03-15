<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\AttributeModel;

class ErgonodeAttributeReader extends AbstractErgonodeReader
{
    private const KEYS = [
        '_code',
        '_type',
        '_scope',
        '_name',
        '_hint',
        '_placeholder',
    ];

    public function read(): ?AttributeModel
    {
        $item = null;

        while ($this->records->valid()) {
            $record = $this->records->current();

            if (null === $item) {
                $item = new AttributeModel(
                    $record['_code'],
                    $record['_type'],
                    $record['_scope']
                );
            } elseif ($item->getCode() !== $record['_code']) {
                break;
            }

            if (!empty($record['_name'])) {
                $item->addName($record['_language'], $record['_name']);
            }
            if (!empty($record['_hint'])) {
                $item->addHint($record['_language'], $record['_hint']);
            }
            if (!empty($record['_placeholder'])) {
                $item->addPlaceholder($record['_language'], $record['_placeholder']);
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
