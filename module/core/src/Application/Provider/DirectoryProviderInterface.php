<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Provider;

interface DirectoryProviderInterface
{
    public function getProjectDirectory(): string;

    public function getMultimediaDirectory(): string;
}
