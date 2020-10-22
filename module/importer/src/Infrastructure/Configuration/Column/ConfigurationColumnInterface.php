<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Configuration\Column;

interface ConfigurationColumnInterface
{
    public function getField(): string;

    public function isImported(): bool;
}
