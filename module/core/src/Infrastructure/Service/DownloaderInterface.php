<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Service;

interface DownloaderInterface
{
    /**
     * @param string $url
     *
     * @return string|null
     */
    public function download(string $url): ?string;
}
