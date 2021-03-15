<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column;

class NumericColumn extends AbstractColumn
{
    public const TYPE = 'NUMERIC';

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
