<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Command\User;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

/**
 */
class ChangeUserAvatarCommand implements DomainCommandInterface
{
    /**
     * @var UserId
     */
    private UserId $id;

    /**
     * @var AvatarId
     */
    private ?AvatarId $avatarId;

    /**
     * @param UserId        $id
     * @param AvatarId|null $avatarId
     */
    public function __construct(UserId $id, ?AvatarId $avatarId = null)
    {
        $this->id = $id;
        $this->avatarId = $avatarId;
    }

    /**
     * @return UserId
     */
    public function getId(): UserId
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
