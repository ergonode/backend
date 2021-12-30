<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\MultimediaModel;

class ErgonodeMultimediaReader extends AbstractErgonodeReader
{
    private const KEYS = [
        '_name',
        '_url',
        '_alt',
        '_language',
    ];

    public function read(): ?MultimediaModel
    {
        $item = null;

        while ($this->records->valid()) {
            $record = $this->records->current();

            if (null === $item) {
                $item = new MultimediaModel(
                    $record['_name'],
                    $record['_url'],
                );
            } elseif ($item->getName() !== $record['_name']) {
                break;
            }

            if (!empty($record['_alt'])) {
                $item->addAlt($record['_language'], $record['_alt']);
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
