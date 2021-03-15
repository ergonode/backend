<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Reader\Infrastructure\Exception;

use Ergonode\Channel\Infrastructure\Exception\ExportException;

class ReaderException extends ExportException
{
    public function __construct(string $message, \Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}
