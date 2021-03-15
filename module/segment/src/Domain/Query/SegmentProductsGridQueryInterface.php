<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Doctrine\DBAL\Query\QueryBuilder;

interface SegmentProductsGridQueryInterface
{
    public function getGridQuery(SegmentId $segmentId): QueryBuilder;
}
