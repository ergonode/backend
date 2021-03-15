<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Query;

use Doctrine\DBAL\Query\QueryBuilder;

interface AccountGridQueryInterface
{
    public function getGridQuery(): QueryBuilder;
}
