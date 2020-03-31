<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Provider;

/**
 */
interface MultimediaFileProviderInterface
{
    /**
     * @param string $filename
     *
     * @return string
     */
    public function getFile(string $filename): string;

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function hasFile(string $filename): bool;
}
