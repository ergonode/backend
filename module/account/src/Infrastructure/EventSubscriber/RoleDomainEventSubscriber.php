<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\EventSubscriber;

use Ergonode\Account\Domain\Event\Role\AddPrivilegeToRoleEvent;
use Ergonode\Account\Domain\Event\Role\RemovePrivilegeFromRoleEvent;
use Ergonode\Account\Domain\Event\Role\RoleCreatedEvent;
use Ergonode\Account\Domain\Event\Role\RoleDescriptionChangedEvent;
use Ergonode\Account\Domain\Event\Role\RoleNameChangedEvent;
use Ergonode\Account\Domain\Event\Role\RolePrivilegesChangedEvent;
use Ergonode\Account\Domain\Event\Role\RoleRemovedEvent;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 */
class RoleDomainEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var DomainEventProjector
     */
    private $projector;

    /**
     * @param DomainEventProjector $projector
     */
    public function __construct(DomainEventProjector $projector)
    {
        $this->projector = $projector;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RoleCreatedEvent::class => 'projection',
            RoleNameChangedEvent::class => 'projection',
            RoleDescriptionChangedEvent::class => 'projection',
            RolePrivilegesChangedEvent::class => 'projection',
            AddPrivilegeToRoleEvent::class => 'projection',
            RemovePrivilegeFromRoleEvent::class => 'projection',
            RoleRemovedEvent::class => 'projection',
        ];
    }

    /**
     * @param DomainEventEnvelope $envelope
     *
     * @throws \Throwable
     */
    public function projection(DomainEventEnvelope $envelope): void
    {
        $this->projector->projection($envelope);
    }
}
