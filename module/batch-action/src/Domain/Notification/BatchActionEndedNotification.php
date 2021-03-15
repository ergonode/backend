<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Notification;

use Ergonode\Notification\Domain\NotificationInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\SharedKernel\Domain\AbstractId;

class BatchActionEndedNotification implements NotificationInterface
{
    private const TYPE = 'batch-action-ended';
    private const MESSAGE = 'Batch action "%type%" ended';

    private string $message;

    private UserId $userId;

    private AbstractId $objectId;

    private array $parameters;

    private \DateTime $createdAt;

    public function __construct(BatchAction $batchAction, UserId $userId)
    {
        $this->message = self::MESSAGE;
        $this->userId = $userId;
        $this->objectId = $batchAction->getId();
        $this->parameters = ['%type%' => $batchAction->getType()->getValue()];
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

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getObjectId(): ?AbstractId
    {
        return $this->objectId;
    }
}
