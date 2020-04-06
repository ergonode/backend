<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Notification;

use Ergonode\Notification\Domain\NotificationInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

/**
 *
 */
class EndImportNotification implements NotificationInterface
{
    private const MESSAGE = 'Import "%import%" ended';

    /**
     * @var string
     */
    private string $message;

    /**
     * @var UserId
     */
    private UserId $userId;

    /**
     * @var array
     */
    private array $parameters;

    /**
     * @var \DateTime
     */
    private \DateTime $createdAt;

    /**
     * @param ImportId $importId
     *
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

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return UserId
     */
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

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return UserId|null
     */
    public function getAuthorId(): ?UserId
    {
        return $this->userId;
    }
}
