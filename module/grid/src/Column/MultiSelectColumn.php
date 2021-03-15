<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column;

class MultiSelectColumn extends AbstractColumn
{
    public const TYPE = 'MULTI_SELECT';

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
