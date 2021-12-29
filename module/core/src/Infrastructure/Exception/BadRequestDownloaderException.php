<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Exception;

class BadRequestDownloaderException extends DownloaderException
{
    private const MESSAGE = 'Can\'t download file from url "%s", bad request';

    public function __construct(string $url)
    {
        parent::__construct(sprintf(self::MESSAGE, $url));
    }
}
