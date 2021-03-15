<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\TemplateElementModel;

class ErgonodeTemplateElementReader extends AbstractErgonodeReader
{
    private const KEYS = [
        '_code',
        '_type',
        '_language',
        '_x',
        '_y',
        '_width',
        '_height',
    ];

    public function read(): ?TemplateElementModel
    {
        $item = null;

        if ($this->records->valid()) {
            $record = $this->records->current();

            $item = new TemplateElementModel(
                $record['_name'],
                $record['_type'],
                (int) $record['_x'],
                (int) $record['_y'],
                (int) $record['_width'],
                (int) $record['_height'],
            );

            foreach ($record as $key => $value) {
                if ('' !== $value && !array_key_exists($key, self::KEYS)) {
                    $item->addParameter($key, $value);
                }
            }

            $this->records->next();
        }

        return $item;
    }

    public function reset(): void
    {
        $this->records->rewind();
    }
}
