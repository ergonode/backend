<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

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
use Webmozart\Assert\Assert;

class Role extends AbstractAggregateRoot
{
    private RoleId $id;

    private string $name;

    private ?string $description;

    /**
     * @var Privilege[]
     */
    private array $privileges;

    private bool $hidden;

    /**
     * @param array $privileges
     *
     * @throws \Exception
     */
    public function __construct(
        RoleId $id,
        string $name,
        ?string $description,
        array $privileges = [],
        bool $hidden = false
    ) {
        Assert::allIsInstanceOf($privileges, Privilege::class);

        $this->apply(new RoleCreatedEvent($id, $name, $description, $privileges, $hidden));
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

    public function changeName(string $name): void
    {
        if ($name !== $this->name) {
            $this->apply(new RoleNameChangedEvent($this->id, $name));
        }
    }

    /**
     * @param array $privileges
     */
    public function changesPrivileges(array $privileges): void
    {
        Assert::allIsInstanceOf($privileges, Privilege::class);

        $this->apply(new RolePrivilegesChangedEvent($this->id, $privileges));
    }

    public function changeDescription(?string $description): void
    {
        if ($description !== $this->description) {
            $this->apply(new RoleDescriptionChangedEvent($this->id, $description));
        }
    }

    public function hasPrivilege(Privilege $privilege): bool
    {
        foreach ($this->privileges as $element) {
            if ($privilege->isEqual($element)) {
                return true;
            }
        }

        return false;
    }

    public function addPrivilege(Privilege $privilege): void
    {
        if ($this->hasPrivilege($privilege)) {
            throw new \RuntimeException(sprintf('Privilege %s already exists', $privilege->getValue()));
        }

        $this->apply(new AddPrivilegeToRoleEvent($this->id, $privilege));
    }

    public function removePrivilege(Privilege $privilege): void
    {
        if (!$this->hasPrivilege($privilege)) {
            throw new \RuntimeException(sprintf('Privilege %s not exists', $privilege->getValue()));
        }

        $this->apply(new RemovePrivilegeFromRoleEvent($this->id, $privilege));
    }

    protected function applyRoleCreatedEvent(RoleCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
        $this->privileges = $event->getPrivileges();
        $this->hidden = false;
    }

    protected function applyAddPrivilegeToRoleEvent(AddPrivilegeToRoleEvent $event): void
    {
        $this->privileges[] = $event->getPrivilege();
    }

    protected function applyRemovePrivilegeFromRoleEvent(RemovePrivilegeFromRoleEvent $event): void
    {
        foreach ($this->privileges as $key => $privilege) {
            if ($privilege->isEqual($event->getPrivilege())) {
                unset($this->privileges[$key]);
            }
        }
    }

    protected function applyRoleNameChangedEvent(RoleNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    protected function applyRoleDescriptionChangedEvent(RoleDescriptionChangedEvent $event): void
    {
        $this->description = $event->getTo();
    }

    protected function applyRolePrivilegesChangedEvent(RolePrivilegesChangedEvent $event): void
    {
        $this->privileges = $event->getTo();
    }
}
