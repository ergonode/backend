<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Notification;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Notification\Domain\NotificationInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;

/**
 */
class StatusChangedNotification implements NotificationInterface
{
    private const MESSAGE = 'Product "%sku%" status was changed from "%from%" to "%to%" by user "%user%"';

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
     * @param Sku        $sku
     * @param StatusCode $from
     * @param StatusCode $to
     * @param User       $user
     *
     * @throws \Exception
     */
    public function __construct(Sku $sku, StatusCode $from, StatusCode $to, User $user)
    {
        $this->createdAt = new \DateTime();
        $this->message = self::MESSAGE;
        $this->parameters = [
            '%sku%' => $sku->getValue(),
            '%from%' => $from->getValue(),
            '%to%' => $to->getValue(),
            '%user%' => sprintf('%s %s', $user->getFirstName(), $user->getLastName()),
        ];
        $this->userId = $user->getId();
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
