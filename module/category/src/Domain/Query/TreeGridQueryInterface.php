<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Doctrine\DBAL\Query\QueryBuilder;

interface TreeGridQueryInterface
{
    public function getDataSet(Language $language): QueryBuilder;
}
