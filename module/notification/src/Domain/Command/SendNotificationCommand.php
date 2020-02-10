<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Notification\Domain\NotificationInterface;

/**
 */
class SendNotificationCommand implements DomainCommandInterface
{
    /**
     * @var NotificationInterface
     */
    private $notification;

    /**
     * @var UserId[]
     */
    private $recipients;

    /**
     * @param NotificationInterface $notification
     * @param UserId[]              $recipients
     */
    public function __construct(NotificationInterface $notification, array $recipients)
    {
        $this->notification = $notification;
        $this->recipients = $recipients;
    }

    /**
     * @return NotificationInterface
     */
    public function getNotification(): NotificationInterface
    {
        return $this->notification;
    }

    /**
     * @return UserId[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }
}
