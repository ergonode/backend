<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Domain\Command;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Entity\UserId;

/**
 */
class SendNotificationCommand
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var RoleId
     */
    private $roleId;

    /**
     * @var UserId|null
     */
    private $authorId;

    /**
     * @param string      $message
     * @param array       $parameters
     * @param RoleId      $roleId
     * @param UserId|null $authorId
     */
    public function __construct(string $message, array $parameters, RoleId $roleId, UserId $authorId = null)
    {
        $this->message = $message;
        $this->parameters = $parameters;
        $this->roleId = $roleId;
        $this->authorId = $authorId;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return RoleId
     */
    public function getRoleId(): RoleId
    {
        return $this->roleId;
    }

    /**
     * @return UserId|null
     */
    public function getAuthorId(): ?UserId
    {
        return $this->authorId;
    }
}
