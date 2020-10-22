<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Exception;

class ConverterException extends \Exception
{
    /**
     * @param string          $message
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
