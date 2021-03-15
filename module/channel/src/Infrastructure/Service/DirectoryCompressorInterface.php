<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Service;

interface DirectoryCompressorInterface
{
    /**
     * @throw \RuntimeException
     */
    public function compress(string $sourceDirectory, string $destinationDirectory, string $fileName): string;
}
