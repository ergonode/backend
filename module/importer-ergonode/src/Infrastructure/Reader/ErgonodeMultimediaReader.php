<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Infrastructure\Reader;

use Ergonode\ImporterErgonode\Infrastructure\Model\MultimediaModel;

/**
 */
final class ErgonodeMultimediaReader extends AbstractErgonodeReader
{
    /**
     * @return MultimediaModel|null
     */
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
            } else if ($item->getId() !== $record['_id']) {
                break;
            }

            $item->addTranslation($record['_language'], $record['_name'], $record['_alt']);
            $this->records->next();
        }

        return $item;
    }
}
