<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ramsey\Uuid\Uuid;

interface NotificationQueryInterface
{
    /**
     * @return array
     */
    public function check(UserId $id): array;

    public function mark(Uuid $id, UserId $userId, \DateTime $readAt): void;

    public function markAll(UserId $userId, \DateTime $readAt): void;
}
