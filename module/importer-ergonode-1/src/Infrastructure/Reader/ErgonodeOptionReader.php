<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Reader;

use Ergonode\ImporterErgonode\Infrastructure\Model\OptionModel;

class ErgonodeOptionReader extends AbstractErgonodeReader
{
    public function read(): ?OptionModel
    {
        $item = null;

        while ($this->records->valid()) {
            $record = $this->records->current();

            if (null === $item) {
                $item = new OptionModel($record['_id'], $record['_code'], $record['_attribute_code']);
            } else if ($item->getId() !== $record['_id']) {
                break;
            }

            $item->addTranslation($record['_language'], $record['_label']);
            $this->records->next();
        }

        return $item;
    }
}
