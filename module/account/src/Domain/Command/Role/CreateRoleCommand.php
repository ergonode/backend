<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Command\Role;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\ValueObject\Privilege;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class CreateRoleCommand implements DomainCommandInterface
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
     * @param string      $name
     * @param string      $description
     * @param Privilege[] $privileges
     *
     * @throws \Exception
     */
    public function __construct(string $name, string $description, array $privileges = [])
    {
        Assert::allIsInstanceOf($privileges, Privilege::class);

        $this->id = RoleId::generate();
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
