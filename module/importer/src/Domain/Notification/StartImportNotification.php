<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Notification;

use Ergonode\Notification\Domain\NotificationInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

class StartImportNotification implements NotificationInterface
{
    private const MESSAGE = 'Import "%import%" started';

    private string $message;

    private UserId $userId;

    /**
     * @var array
     */
    private array $parameters;

    private \DateTime $createdAt;

    /**
     * @throws \Exception
     */
    public function __construct(ImportId $importId)
    {
        $this->createdAt = new \DateTime();
        $this->message = self::MESSAGE;
        $this->parameters = [
            '%import%' => $importId->getValue(),
        ];
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getAuthorId(): ?UserId
    {
        return $this->userId;
    }
}
