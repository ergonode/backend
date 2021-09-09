<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class MarkAllNotificationsCommand implements NotificationCommandInterface
{
    private UserId $userId;

    private \DateTime $readAt;

    public function __construct(UserId $userId, \DateTime $readAt)
    {
        $this->userId = $userId;
        $this->readAt = $readAt;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getReadAt(): \DateTime
    {
        return $this->readAt;
    }
}
