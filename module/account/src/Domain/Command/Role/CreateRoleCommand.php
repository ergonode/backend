<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Command\Role;

use Ergonode\Account\Domain\Command\AccountCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\ValueObject\Privilege;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

class CreateRoleCommand implements AccountCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\RoleId")
     */
    private RoleId $id;

    /**
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @JMS\Type("string")
     */
    private ?string $description;

    /**
     * @var Privilege[]
     *
     * @JMS\Type("array<Ergonode\Account\Domain\ValueObject\Privilege>")
     */
    private array $privileges;

    /**
     * @JMS\Type("bool")
     */
    private bool $hidden;

    /**
     * @param Privilege[] $privileges
     *
     * @throws \Exception
     */
    public function __construct(string $name, ?string $description = null, array $privileges = [], bool $hidden = false)
    {
        Assert::allIsInstanceOf($privileges, Privilege::class);

        $this->id = RoleId::generate();
        $this->name = $name;
        $this->description = $description;
        $this->privileges = $privileges;
        $this->hidden = $hidden;
    }

    public function getId(): RoleId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
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

    public function isHidden(): bool
    {
        return $this->hidden;
    }
}
