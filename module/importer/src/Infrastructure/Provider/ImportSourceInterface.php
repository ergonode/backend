<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Provider;

interface ImportSourceInterface
{
    public static function getType(): string;

    public function supported(string $type): bool;
}
