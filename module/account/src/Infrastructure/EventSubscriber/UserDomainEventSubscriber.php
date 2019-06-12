<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\EventSubscriber;

use Ergonode\Account\Domain\Event\UserAvatarChangedEvent;
use Ergonode\Account\Domain\Event\UserCreatedEvent;
use Ergonode\Account\Domain\Event\UserFirstNameChangedEvent;
use Ergonode\Account\Domain\Event\UserLanguageChangedEvent;
use Ergonode\Account\Domain\Event\UserLastNameChangedEvent;
use Ergonode\Account\Domain\Event\UserPasswordChangedEvent;
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
