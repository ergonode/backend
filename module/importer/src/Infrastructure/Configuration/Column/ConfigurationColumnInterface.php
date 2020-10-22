<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Configuration\Column;

interface ConfigurationColumnInterface
{
    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @return bool
     */
    public function isImported(): bool;
}
