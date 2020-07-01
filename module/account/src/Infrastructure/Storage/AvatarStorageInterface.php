<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Storage;

use League\Flysystem\FileNotFoundException;

/**
 */
interface AvatarStorageInterface
{
    /**
     * @param string $filename
     *
     * @return string
     */
    public function read(string $filename): string;

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function has(string $filename): bool;

    /**
     * @param string $filename
     * @param string $content
     */
    public function write(string $filename, string $content): void;

    /**
     * @param string $filename
     *
     * @return array
     */
    public function info(string $filename): array;
}
