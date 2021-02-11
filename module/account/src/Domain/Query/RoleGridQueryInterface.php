<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Query;

use Doctrine\DBAL\Query\QueryBuilder;

interface RoleGridQueryInterface
{
    public function getGridQuery(): QueryBuilder;
}
