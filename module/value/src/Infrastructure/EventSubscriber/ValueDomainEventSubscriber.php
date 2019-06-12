<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\EventSubscriber;

use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjector;
use Ergonode\Value\Domain\Event\ValueAddedEvent;
use Ergonode\Value\Domain\Event\ValueChangedEvent;
use Ergonode\Value\Domain\Event\ValueRemovedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 */
class ValueDomainEventSubscriber implements EventSubscriberInterface
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
            ValueAddedEvent::class => 'projection',
            ValueChangedEvent::class => 'projection',
            ValueRemovedEvent::class => 'projection',
        ];
    }

    /**
     * @param DomainEventEnvelope $envelope
     *
     * @throws \Exception
     */
    public function projection(DomainEventEnvelope $envelope): void
    {
        $this->projector->projection($envelope);
    }
}
