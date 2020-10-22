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
     * @var UserId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $id;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     */
    private ?string $avatarFilename;

    /**
     * @param UserId      $id
     * @param string|null $avatarFilename
     */
    public function __construct(UserId $id, string $avatarFilename = null)
    {
        $this->id = $id;
        $this->avatarFilename = $avatarFilename;
    }

    /**
     * @return UserId
     */
    public function getAggregateId(): UserId
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAvatarFilename(): ?string
    {
        return $this->avatarFilename;
    }
}
