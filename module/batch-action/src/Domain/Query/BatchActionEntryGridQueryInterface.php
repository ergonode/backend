<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;

interface BatchActionEntryGridQueryInterface
{
    public function getGridQuery(BatchActionId $id): QueryBuilder;
}
