<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Grid\Column;

use Ergonode\Grid\Column\AbstractColumn;

class BatchActionMessageColumn extends AbstractColumn
{
    public const TYPE = 'TEXT_AREA';

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
