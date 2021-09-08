<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

interface SourceQueryInterface
{
    /**
     * @return SourceId[]
     */
    public function findSourceIdsByType(string $type): array;
}
