<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\TemplateModel;

class ErgonodeTemplateReader extends AbstractErgonodeReader
{
    private const KEYS = [
        '_name',
        '_language',
    ];

    public function read(): ?TemplateModel
    {
        $item = null;

        if ($this->records->valid()) {
            $record = $this->records->current();

            $item = new TemplateModel(
                $record['_name'],
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
}
