<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

/**
 */
interface ImportActionInterface
{
    /**
     * @param ImportId $importId
     * @param Record   $record
     */
    public function action(ImportId $importId, Record $record): void;

    /**
     * @return string
     */
    public function getType(): string;
}
