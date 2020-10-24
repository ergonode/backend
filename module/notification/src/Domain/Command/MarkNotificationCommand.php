<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ramsey\Uuid\Uuid;

class MarkNotificationCommand implements DomainCommandInterface
{
    private Uuid $notificationId;

    private UserId $userId;

    private \DateTime $readAt;

    public function __construct(Uuid $notificationId, UserId $userId, \DateTime $readAt)
    {
        $this->notificationId = $notificationId;
        $this->userId = $userId;
        $this->readAt = $readAt;
    }

    public function getNotificationId(): Uuid
    {
        return $this->notificationId;
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
