<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Infrastructure\Reader;

use Ergonode\ImporterErgonode\Infrastructure\Model\AttributeModel;

/**
 */
final class ErgonodeAttributeReader extends AbstractErgonodeReader
{
    /**
     * @return AttributeModel|null
     */
    public function read(): ?AttributeModel
    {
        $item = null;

        while ($this->records->valid()) {
            $record = $this->records->current();

            if (null === $item) {
                $item = new AttributeModel(
                    $record['_id'],
                    $record['_code'],
                    $record['_type'],
                    $record['_hint'], // @todo is translatable?
                    $record['_placeholder']
                );
            } else if ($item->getId() !== $record['_id']) {
                break;
            }

            $item->addTranslation($record['_language'], $record['_name']);
            $this->records->next();
        }

        return $item;
    }
}
