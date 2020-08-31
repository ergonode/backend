<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Infrastructure\Exception;

use Ergonode\Exporter\Infrastructure\Exception\ExportException;

/**
 */
class ReaderException extends ExportException
{
    /**
     * @param string         $message
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, \Throwable $previous = null)
    {
        parent::__construct($message,  $previous);
    }
}