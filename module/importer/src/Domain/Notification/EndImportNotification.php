<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Notification;

use Ergonode\Notification\Domain\NotificationInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\AbstractId;

class EndImportNotification implements NotificationInterface
{
    private const TYPE = 'import-ended';
    private const MESSAGE = 'Import "%import%" ended';

    private string $message;
    private UserId $authorId;
    private AggregateId $objectId;
    private \DateTime $createdAt;

    /**
     * @throws \Exception
     */
    public function __construct(ImportId $importId)
    {
        $this->createdAt = new \DateTime();
        $this->message = self::MESSAGE;
        $this->objectId = $importId;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getAuthorId(): ?UserId
    {
        return $this->authorId;
    }

    public function getObjectId(): ?AbstractId
    {
        return $this->objectId;
    }

    /**
     * @return string[]
     */
    public function getParameters(): array
    {
        return [
            '%import%' => $this->objectId->getValue(),
        ];
    }
}
