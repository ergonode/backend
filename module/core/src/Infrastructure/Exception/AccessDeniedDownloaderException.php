<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Exception;

class AccessDeniedDownloaderException extends DownloaderException
{
    private const MESSAGE = 'Can\'t download file from url "%s", access denied';

    public function __construct(string $url)
    {
        parent::__construct(sprintf(self::MESSAGE, $url));
    }
}
