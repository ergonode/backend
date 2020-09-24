<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Entity;

use Ergonode\Account\Domain\Event\Role\AddPrivilegeToRoleEvent;
use Ergonode\Account\Domain\Event\Role\RemovePrivilegeFromRoleEvent;
use Ergonode\Account\Domain\Event\Role\RoleCreatedEvent;
use Ergonode\Account\Domain\Event\Role\RoleDescriptionChangedEvent;
use Ergonode\Account\Domain\Event\Role\RoleNameChangedEvent;
use Ergonode\Account\Domain\Event\Role\RolePrivilegesChangedEvent;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\AggregateId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class Role extends AbstractAggregateRoot
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
     * @param RoleId $id
     * @param string $name
     * @param string $description
     * @param array  $privileges
     * @param bool   $hidden
     *
     * @throws \Exception
     */
    public function __construct(
        RoleId $id,
        string $name,
        string $description,
        array $privileges = [],
        bool $hidden = false
    ) {
        Assert::allIsInstanceOf($privileges, Privilege::class);

        $this->apply(new RoleCreatedEvent($id, $name, $description, $privileges, $hidden));
    }

    /**
     * @return RoleId|AggregateId
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

    /**
     * @param string $name
     */
    public function changeName(string $name): void
    {
        if ($name !== $this->name) {
            $this->apply(new RoleNameChangedEvent($this->id, $this->name, $name));
        }
    }

    /**
     * @param array $privileges
     */
    public function changesPrivileges(array $privileges): void
    {
        Assert::allIsInstanceOf($privileges, Privilege::class);

        $this->apply(new RolePrivilegesChangedEvent($this->id, $this->privileges, $privileges));
    }

    /**
     * @param string $description
     */
    public function changeDescription(string $description): void
    {
        if ($description !== $this->description) {
            $this->apply(new RoleDescriptionChangedEvent($this->id, $this->description, $description));
        }
    }

    /**
     * @param Privilege $privilege
     *
     * @return bool
     */
    public function hasPrivilege(Privilege $privilege): bool
    {
        foreach ($this->privileges as $element) {
            if ($privilege->isEqual($element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Privilege $privilege
     */
    public function addPrivilege(Privilege $privilege): void
    {
        if ($this->hasPrivilege($privilege)) {
            throw new \RuntimeException(sprintf('Privilege %s already exists', $privilege->getValue()));
        }

        $this->apply(new AddPrivilegeToRoleEvent($this->id, $privilege));
    }

    /**
     * @param Privilege $privilege
     */
    public function removePrivilege(Privilege $privilege): void
    {
        if (!$this->hasPrivilege($privilege)) {
            throw new \RuntimeException(sprintf('Privilege %s not exists', $privilege->getValue()));
        }

        $this->apply(new RemovePrivilegeFromRoleEvent($this->id, $privilege));
    }

    /**
     * @param RoleCreatedEvent $event
     */
    protected function applyRoleCreatedEvent(RoleCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
        $this->privileges = $event->getPrivileges();
        $this->hidden = false;
    }

    /**
     * @param AddPrivilegeToRoleEvent $event
     */
    protected function applyAddPrivilegeToRoleEvent(AddPrivilegeToRoleEvent $event): void
    {
        $this->privileges[] = $event->getPrivilege();
    }

    /**
     * @param RemovePrivilegeFromRoleEvent $event
     */
    protected function applyRemovePrivilegeFromRoleEvent(RemovePrivilegeFromRoleEvent $event): void
    {
        foreach ($this->privileges as $key => $privilege) {
            if ($privilege->isEqual($event->getPrivilege())) {
                unset($this->privileges[$key]);
            }
        }
    }

    /**
     * @param RoleNameChangedEvent $event
     */
    protected function applyRoleNameChangedEvent(RoleNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    /**
     * @param RoleDescriptionChangedEvent $event
     */
    protected function applyRoleDescriptionChangedEvent(RoleDescriptionChangedEvent $event): void
    {
        $this->description = $event->getTo();
    }

    /**
     * @param RolePrivilegesChangedEvent $event
     */
    protected function applyRolePrivilegesChangedEvent(RolePrivilegesChangedEvent $event): void
    {
        $this->privileges = $event->getTo();
    }
}
