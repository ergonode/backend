<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Provider;

/**
 */
interface ImportSourceInterface
{
    /**
     * @return string
     */
    public static function getType(): string;

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;
}
