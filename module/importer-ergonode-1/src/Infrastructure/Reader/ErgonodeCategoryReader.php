<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\CategoryModel;

class ErgonodeCategoryReader extends AbstractErgonodeReader
{
    public function read(): ?CategoryModel
    {
        $item = null;

        while ($this->records->valid()) {
            $record = $this->records->current();

            if (null === $item) {
                $item = new CategoryModel($record['_id'], $record['_code']);
            } elseif ($item->getId() !== $record['_id']) {
                break;
            }

            $item->addTranslation($record['_language'], $record['_name']);
            $this->records->next();
        }

        return $item;
    }
}
