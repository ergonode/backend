<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Exception;

class ConverterException extends \Exception
{
    public function __construct(string $message = '', \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
