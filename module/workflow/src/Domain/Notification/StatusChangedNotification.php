<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Notification;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Notification\Domain\NotificationInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;

class StatusChangedNotification implements NotificationInterface
{
    private const MESSAGE = 'Product "%sku%" status was changed from "%from%" to "%to%" '.
    'in language "%language%" by user "%user%"';

    private string $message;

    private UserId $userId;

    private array $parameters;

    private \DateTime $createdAt;

    /**
     * @throws \Exception
     */
    public function __construct(Sku $sku, StatusCode $from, StatusCode $to, User $user, ?Language $language = null)
    {
        $this->createdAt = new \DateTime();
        $this->message = self::MESSAGE;
        $this->parameters = [
            '%sku%' => $sku->getValue(),
            '%from%' => $from->getValue(),
            '%to%' => $to->getValue(),
            '%user%' => sprintf('%s %s', $user->getFirstName(), $user->getLastName()),
            '%language%' => $language ? $language->getCode() : null,
        ];
        $this->userId = $user->getId();
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
