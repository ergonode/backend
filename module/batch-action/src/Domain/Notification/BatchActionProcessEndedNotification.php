<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Notification;

use Ergonode\Notification\Domain\NotificationInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class BatchActionProcessEndedNotification implements NotificationInterface
{
    private const MESSAGE = 'Batch action "%type%" ended';

    private string $message;

    private UserId $userId;

    private array $parameters;

    private \DateTime $createdAt;


    public function __construct(string $type, UserId $userId)
    {
        $this->message = self::MESSAGE;
        $this->userId = $userId;
        $this->parameters = ['%type%' => $type];
        $this->createdAt = new \DateTime();
    }


    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getAuthorId(): ?UserId
    {
        return $this->userId;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
