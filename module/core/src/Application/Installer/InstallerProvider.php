<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Installer;

/**
 */
class InstallerProvider
{
    /**
     * @var InstallerInterface[]
     */
    private array $collection;

    /**
     * @param InstallerInterface ...$collection
     */
    public function __construct(InstallerInterface ...$collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return InstallerInterface[]
     */
    public function get(): array
    {
        return $this->collection;
    }
}
