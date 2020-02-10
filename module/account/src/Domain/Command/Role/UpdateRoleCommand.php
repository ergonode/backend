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

/**
 */
class UpdateRoleCommand implements DomainCommandInterface
{
    /**
     * @var RoleId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\RoleId")
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
     * @param RoleId      $id
     * @param string      $name
     * @param string      $description
     * @param Privilege[] $privileges
     */
    public function __construct(RoleId $id, string $name, string $description, array $privileges = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->privileges = $privileges;
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
}
