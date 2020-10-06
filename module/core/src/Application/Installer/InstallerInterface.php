<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Installer;

/**
 */
interface InstallerInterface
{
    /**
     * @throws \Exception
     */
    public function install(): void;
}
