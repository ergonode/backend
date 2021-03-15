<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Provider;

class SimpleDirectoryProvider implements DirectoryProviderInterface
{
    public const MULTIMEDIA = 'public/multimedia';

    private string $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    public function getProjectDirectory(): string
    {
        return  $this->directory;
    }

    public function getMultimediaDirectory(): string
    {
        return sprintf('%s/%s', $this->directory, self::MULTIMEDIA);
    }
}
