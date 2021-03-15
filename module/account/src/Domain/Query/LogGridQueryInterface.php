<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Doctrine\DBAL\Query\QueryBuilder;

interface LogGridQueryInterface
{
    public function getDataSet(?UserId $id = null): QueryBuilder;
}
