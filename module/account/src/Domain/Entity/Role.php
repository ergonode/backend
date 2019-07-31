<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Entity;

use Ergonode\Account\Domain\Event\Role\AddPrivilegeToRoleEvent;
use Ergonode\Account\Domain\Event\Role\RemovePrivilegeFromRoleEvent;
use Ergonode\Account\Domain\Event\Role\RoleCreatedEvent;
use Ergonode\Account\Domain\Event\Role\RoleDescriptionChangedEvent;
use Ergonode\Account\Domain\Event\Role\RoleNameChangedEvent;
use Ergonode\Account\Domain\Event\Role\RolePrivilegesChangedEvent;
use Ergonode\Account\Domain\Event\Role\RoleRemovedEvent;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\State;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Webmozart\Assert\Assert;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Role extends AbstractAggregateRoot
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
     * @var State
     *
     * @JMS\Exclude()
     */
    private $state;

    /**
     * @param RoleId      $id
     * @param string      $name
     * @param string      $description
     * @param Privilege[] $privileges
     */
    public function __construct(RoleId $id, string $name, string $description, array $privileges = [])
    {
        Assert::allIsInstanceOf($privileges, Privilege::class);

        $this->apply(new RoleCreatedEvent($id, $name, $description, $privileges));
    }

    /**
     */
    public function remove(): void
    {
        if ($this->state->getValue() !== State::STATE_DELETED) {
            $this->apply(new RoleRemovedEvent());
        }
    }

    /**
     * @return RoleId|AbstractId
     */
    public function getId(): AbstractId
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
     * @param string $name
     */
    public function changeName(string $name): void
    {
        if ($name !== $this->name) {
            $this->apply(new RoleNameChangedEvent($this->name, $name));
        }
    }

    /**
     * @param array $privileges
     */
    public function changesPrivileges(array $privileges): void
    {
        Assert::allIsInstanceOf($privileges, Privilege::class);

        $this->apply(new RolePrivilegesChangedEvent($this->privileges, $privileges));
    }

    /**
     * @param string $description
     */
    public function changeDescription(string $description): void
    {
        if ($description !== $this->name) {
            $this->apply(new RoleDescriptionChangedEvent($this->description, $description));
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

        $this->apply(new AddPrivilegeToRoleEvent($privilege));
    }

    /**
     * @param Privilege $privilege
     */
    public function removePrivilege(Privilege $privilege): void
    {
        if (!$this->hasPrivilege($privilege)) {
            throw new \RuntimeException(sprintf('Privilege %s not exists', $privilege->getValue()));
        }

        $this->apply(new RemovePrivilegeFromRoleEvent($privilege));
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->state->getValue() === State::STATE_DELETED;
    }

    /**
     * @param RoleCreatedEvent $event
     */
    protected function applyRoleCreatedEvent(RoleCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
        $this->privileges = $event->getPrivileges();
        $this->state = new State();
    }

    /**
     * @param RoleRemovedEvent $event
     */
    protected function applyRoleRemovedEvent(RoleRemovedEvent $event): void
    {
        $this->state = new State(State::STATE_DELETED);
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
