<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\TemplateElementModel;

class ErgonodeTemplateElementReader extends AbstractErgonodeReader
{
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
                json_decode($record['_properties'], true, 512, JSON_THROW_ON_ERROR),
            );

            $this->records->next();
        }

        return $item;
    }

    public function reset(): void
    {
        $this->records->rewind();
    }
}
