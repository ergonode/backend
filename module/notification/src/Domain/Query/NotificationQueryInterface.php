<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ramsey\Uuid\Uuid;
use Doctrine\DBAL\DBALException;

interface NotificationQueryInterface
{
    public function getDataSet(UserId $id, Language $language): DataSetInterface;

    /**
     * @return array
     */
    public function check(UserId $id): array;

    /**
     * @throws DBALException
     */
    public function mark(Uuid $id, UserId $userId, \DateTime $readAt): void;

    /**
     * @throws DBALException
     */
    public function markAll(UserId $userId, \DateTime $readAt): void;
}
