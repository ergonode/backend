<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\OptionModel;

class ErgonodeOptionReader extends AbstractErgonodeReader
{
    private const KEYS = [
        '_code',
        '_label',
        '_attribute',
        '_language',
    ];

    public function read(): ?OptionModel
    {
        $item = null;

        while ($this->records->valid()) {
            $record = $this->records->current();
            if (null === $item) {
                $item = new OptionModel($record['_code'], $record['_attribute']);
            } elseif ($item->getCode() !== $record['_code'] || $item->getAttribute() !== $record['_attribute']) {
                break;
            }
            if (!empty($record['_label'])) {
                $item->addTranslation($record['_language'], $record['_label']);
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
