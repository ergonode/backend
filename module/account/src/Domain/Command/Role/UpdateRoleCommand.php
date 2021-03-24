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

class UpdateRoleCommand implements AccountCommandInterface
{
    private RoleId $id;

    private string $name;

    private ?string $description;

    /**
     * @var Privilege[]
     */
    private array $privileges;

    /**
     * @param Privilege[] $privileges
     */
    public function __construct(RoleId $id, string $name, ?string $description = null, array $privileges = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->privileges = $privileges;
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
}
