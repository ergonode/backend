<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service;

/**
 */
class FileExistCheckService
{
    /**
     * @param string $file
     *
     * @return bool
     */
    public function check(string $file): bool
    {
        return file_exists($file);
    }
}
