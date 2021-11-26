<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Service;

use Ergonode\Core\Infrastructure\Exception\DownloaderException;

interface DownloaderInterface
{
    /**
     * @throws DownloaderException
     */
    public function download(string $url, array $headers = []): string;
}
