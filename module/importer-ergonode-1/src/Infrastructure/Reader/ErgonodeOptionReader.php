<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\OptionModel;

class ErgonodeOptionReader extends AbstractErgonodeReader
{
    public function read(): ?OptionModel
    {
        $item = null;

        while ($this->records->valid()) {
            $record = $this->records->current();
            if (null === $item) {
                $item = new OptionModel($record['_code'], $record['_attribute_code']);
            } elseif ($item->getCode() !== $record['_code'] || $item->getAttribute() !== $record['_attribute_code']) {
                break;
            }
            if (!empty($record['_label'])) {
                $item->addTranslation($record['_language'], $record['_label']);
            }

            $this->records->next();
        }

        return $item;
    }
}
