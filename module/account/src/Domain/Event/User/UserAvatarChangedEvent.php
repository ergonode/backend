<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UserAvatarChangedEvent implements DomainEventInterface
{
    /**
     * @var UserId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $id;

    /**
     * @var AvatarId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AvatarId")
     */
    private ?AvatarId $avatarId;

    /**
     * @param UserId        $id
     * @param AvatarId|null $avatarId
     */
    public function __construct(UserId $id, AvatarId $avatarId = null)
    {
        $this->id = $id;
        $this->avatarId = $avatarId;
    }

    /**
     * @return UserId
     */
    public function getAggregateId(): UserId
    {
        return $this->id;
    }

    /**
     * @return AvatarId|null
     */
    public function getAvatarId(): ?AvatarId
    {
        return $this->avatarId;
    }
}
