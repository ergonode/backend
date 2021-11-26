<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Exception;

class DownloaderException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
