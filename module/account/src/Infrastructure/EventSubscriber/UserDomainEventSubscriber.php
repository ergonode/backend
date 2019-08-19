<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\EventSubscriber;

use Ergonode\Account\Domain\Event\User\UserActivityChangedEvent;
use Ergonode\Account\Domain\Event\User\UserAvatarChangedEvent;
use Ergonode\Account\Domain\Event\User\UserCreatedEvent;
use Ergonode\Account\Domain\Event\User\UserFirstNameChangedEvent;
use Ergonode\Account\Domain\Event\User\UserLanguageChangedEvent;
use Ergonode\Account\Domain\Event\User\UserLastNameChangedEvent;
use Ergonode\Account\Domain\Event\User\UserPasswordChangedEvent;
use Ergonode\Account\Domain\Event\User\UserRoleChangedEvent;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 */
class UserDomainEventSubscriber implements EventSubscriberInterface
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
            UserCreatedEvent::class => 'projection',
            UserAvatarChangedEvent::class => 'projection',
            UserPasswordChangedEvent::class => 'projection',
            UserFirstNameChangedEvent::class => 'projection',
            UserLastNameChangedEvent::class => 'projection',
            UserLanguageChangedEvent::class => 'projection',
            UserRoleChangedEvent::class => 'projection',
            UserActivityChangedEvent::class => 'projection',
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
