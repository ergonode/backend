<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Command\Role;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class CreateRoleCommand implements DomainCommandInterface
{
    /**
     * @var RoleId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\RoleId")
     */
    private RoleId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $description;

    /**
     * @var Privilege[]
     *
     * @JMS\Type("array<Ergonode\Account\Domain\ValueObject\Privilege>")
     */
    private array $privileges;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private bool $hidden;

    /**
     * @param string      $name
     * @param string      $description
     * @param Privilege[] $privileges
     * @param bool        $hidden
     *
     * @throws \Exception
     */
    public function __construct(string $name, string $description, array $privileges = [], bool $hidden = false)
    {
        Assert::allIsInstanceOf($privileges, Privilege::class);

        $this->id = RoleId::generate();
        $this->name = $name;
        $this->description = $description;
        $this->privileges = $privileges;
        $this->hidden = $hidden;
    }

    /**
     * @return RoleId
     */
    public function getId(): RoleId
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
