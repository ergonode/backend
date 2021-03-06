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
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class StatusChangedNotification implements NotificationInterface
{
    private const TYPE = 'status-changed';
    private const MESSAGE = 'Product "%sku%" status was changed from "%from%" to "%to%" '.
    'in language "%language%" by user "%user%"';

    private string $message;

    private UserId $authorId;

    private AggregateId $objectId;

    private array $parameters;

    private \DateTime $createdAt;

    /**
     * @throws \Exception
     */
    public function __construct(
        ProductId $id,
        Sku $sku,
        StatusCode $from,
        StatusCode $to,
        User $user,
        ?Language $language = null
    ) {
        $this->createdAt = new \DateTime();
        $this->message = self::MESSAGE;
        $this->objectId = $id;
        $this->parameters = [
            '%sku%' => $sku->getValue(),
            '%from%' => $from->getValue(),
            '%to%' => $to->getValue(),
            '%user%' => sprintf('%s %s', $user->getFirstName(), $user->getLastName()),
            '%language%' => $language ? $language->getCode() : null,
        ];
        $this->authorId = $user->getId();
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

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getObjectId(): AggregateId
    {
        return $this->objectId;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
