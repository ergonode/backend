<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Query;

use Doctrine\DBAL\Query\QueryBuilder;

interface SourceGridQueryInterface
{
    public function getGridQuery(): QueryBuilder;
}
