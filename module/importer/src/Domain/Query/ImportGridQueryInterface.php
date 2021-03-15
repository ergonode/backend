<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Doctrine\DBAL\Query\QueryBuilder;

interface ImportGridQueryInterface
{
    public function gteGridQuery(SourceId $id): QueryBuilder;
}
