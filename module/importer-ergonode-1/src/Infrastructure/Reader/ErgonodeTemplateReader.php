<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Infrastructure\Reader;

use Ergonode\ImporterErgonode\Infrastructure\Model\TemplateModel;

/**
 */
final class ErgonodeTemplateReader extends AbstractErgonodeReader
{
    /**
     * @return TemplateModel|null
     */
    public function read(): ?TemplateModel
    {
        $item = null;

        if ($this->records->valid()) {
            $record = $this->records->current();

            $item = new TemplateModel(
                $record['_id'],
                $record['_name'],
                $record['_type'],
                (int) $record['_x'],
                (int) $record['_y'],
                (int) $record['_width'],
                (int) $record['_height'],
                $record['_properties']
            );

            $this->records->next();
        }

        return $item;
    }
}
