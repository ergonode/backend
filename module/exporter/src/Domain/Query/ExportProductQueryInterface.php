<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Query;

/**
 */
interface ExportProductQueryInterface
{
    /**
     * @return array|null
     */
    public function getAllIds(): ?array;
}
