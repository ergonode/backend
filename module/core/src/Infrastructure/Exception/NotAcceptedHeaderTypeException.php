<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Exception;

class NotAcceptedHeaderTypeException extends DownloaderException
{
    private const MESSAGE = 'Unacceptable header Type "%s"';

    public function __construct(array $headerType)
    {
        parent::__construct(sprintf(self::MESSAGE, implode(', ', $headerType)));
    }
}
