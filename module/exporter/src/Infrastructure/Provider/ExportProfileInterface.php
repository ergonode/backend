<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Provider;

/**
 */
interface ExportProfileInterface
{
    /**
     * @return string
     */
    public function getType():string;

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;
}
