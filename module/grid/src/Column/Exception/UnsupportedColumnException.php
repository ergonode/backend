<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column\Exception;

use Ergonode\Grid\ColumnInterface;

class UnsupportedColumnException extends \Exception
{
    public function __construct(ColumnInterface $column)
    {
        $message = sprintf('Unsupported column type "%s" (%s)', $column->getType(), get_class($column));

        parent::__construct($message);
    }
}
