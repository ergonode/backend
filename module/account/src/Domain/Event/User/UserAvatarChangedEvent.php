<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

class UserAvatarChangedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $id;

    /**
     * @JMS\Type("string")
     */
    private ?string $avatarFilename;

    public function __construct(UserId $id, string $avatarFilename = null)
    {
        $this->id = $id;
        $this->avatarFilename = $avatarFilename;
    }

    public function getAggregateId(): UserId
    {
        return $this->id;
    }

    public function getAvatarFilename(): ?string
    {
        return $this->avatarFilename;
    }
}
