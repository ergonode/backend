<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration\Provider;

/**
 */
interface MigrationDirectoryProviderInterface
{
    /**
     * @return array
     */
    public function getDirectoryCollection(): array;

    /**
     * @return string
     */
    public function getMainDirectory(): string;
}
