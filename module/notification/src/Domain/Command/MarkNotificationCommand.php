<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class MarkNotificationCommand implements DomainCommandInterface
{
    /**
     * @var Uuid
     */
    private Uuid $notificationId;

    /**
     * @var UserId
     */
    private UserId $userId;

    /**
     * @var \DateTime
     */
    private \DateTime $readAt;

    /**
     * @param Uuid      $notificationId
     * @param UserId    $userId
     * @param \DateTime $readAt
     */
    public function __construct(Uuid $notificationId, UserId $userId, \DateTime $readAt)
    {
        $this->notificationId = $notificationId;
        $this->userId = $userId;
        $this->readAt = $readAt;
    }

    /**
     * @return Uuid
     */
    public function getNotificationId(): Uuid
    {
        return $this->notificationId;
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return \DateTime
     */
    public function getReadAt(): \DateTime
    {
        return $this->readAt;
    }
}
