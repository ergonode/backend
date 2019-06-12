<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Provider;

/**
 */
interface DirectoryProviderInterface
{
    /**
     * @return string
     */
    public function getProjectDirectory(): string;

    /**
     * @return string
     */
    public function getMultimediaDirectory(): string;
}
