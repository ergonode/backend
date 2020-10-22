<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Service;

interface DirectoryCompressorInterface
{
    /**
     * @param string $sourceDirectory
     * @param string $destinationDirectory
     * @param string $fileName
     *
     * @return string
     *
     * @throw \RuntimeException
     */
    public function compress(string $sourceDirectory, string $destinationDirectory, string $fileName): string;
}
