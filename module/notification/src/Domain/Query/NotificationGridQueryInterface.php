<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Core\Domain\ValueObject\Language;
use Doctrine\DBAL\Query\QueryBuilder;

interface NotificationGridQueryInterface
{
    public function getGridQuery(UserId $id, Language $language): QueryBuilder;
}
