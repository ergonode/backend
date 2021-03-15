<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Query;

use Doctrine\DBAL\Query\QueryBuilder;

interface TemplateElementGridQueryInterface
{
    public function getGridQuery(): QueryBuilder;
}
