<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;

interface FilteredQueryBuilderInterface
{
    public function build(BatchActionFilterInterface $filter): QueryBuilder;
}
