<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action;

use Ergonode\Transformer\Domain\Model\Record;

/**
 */
interface ImportActionInterface
{
    /**
     * @param Record $record
     */
    public function action(Record $record): void;

    /**
     * @return string
     */
    public function getType(): string;
}
