<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column;

class FileColumn extends AbstractColumn
{
    public const TYPE = 'FILE';

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
