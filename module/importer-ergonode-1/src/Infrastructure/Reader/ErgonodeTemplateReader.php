<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\TemplateModel;

class ErgonodeTemplateReader extends AbstractErgonodeReader
{
    public function read(): ?TemplateModel
    {
        $item = null;

        if ($this->records->valid()) {
            $record = $this->records->current();

            $item = new TemplateModel(
                $record['_name'],
            );

            $this->records->next();
        }

        return $item;
    }
}
