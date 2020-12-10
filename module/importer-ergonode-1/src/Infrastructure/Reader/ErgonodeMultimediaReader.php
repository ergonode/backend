<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\MultimediaModel;

class ErgonodeMultimediaReader extends AbstractErgonodeReader
{
    public function read(): ?MultimediaModel
    {
        $item = null;

        while ($this->records->valid()) {
            $record = $this->records->current();

            if (null === $item) {
                $item = new MultimediaModel(
                    $record['_id'],
                    $record['_filename'],
                    $record['_extension'],
                    $record['_mime'],
                    $record['_size'],
                );
            } elseif ($item->getId() !== $record['_id']) {
                break;
            }

            $item->addTranslation($record['_language'], $record['_name'], $record['_alt']);
            $this->records->next();
        }

        return $item;
    }
}
