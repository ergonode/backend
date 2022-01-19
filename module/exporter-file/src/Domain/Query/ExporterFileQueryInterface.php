<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

interface ExporterFileQueryInterface
{
    /**
     * @return array
     */
    public function getAllEditedProductsInChannel(SegmentId $segmentId, ?\DateTime $dateTime = null): array;
}
