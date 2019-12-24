<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class RoleCreatedEvent implements DomainEventInterface
{
    /**
     * @var RoleId
     *
     * @JMS\Type("Ergonode\Account\Domain\Entity\RoleId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $description;

    /**
     * @var Privilege[]
     *
     * @JMS\Type("array<Ergonode\Account\Domain\ValueObject\Privilege>")
     */
    private $privileges;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private $hidden;

    /**
     * @param RoleId      $id
     * @param string      $name
     * @param string      $description
     * @param Privilege[] $privileges
     * @param bool        $hidden
     */
    public function __construct(
        RoleId $id,
        string $name,
        string $description,
        array $privileges = [],
        bool $hidden = false
    ) {
        Assert::allIsInstanceOf($privileges, Privilege::class);

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->privileges = $privileges;
        $this->hidden = $hidden;
    }

    /**
     * @return RoleId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return Privilege[]
     */
    public function getPrivileges(): array
    {
        return $this->privileges;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }
}
