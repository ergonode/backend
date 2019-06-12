<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\EventSubscriber;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeArrayParameterChangeEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeCreatedEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeHintChangedEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeLabelChangedEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeParameterChangeEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributePlaceholderChangedEvent;
use Ergonode\Attribute\Domain\Event\AttributeGroupAddedEvent;
use Ergonode\Attribute\Domain\Event\AttributeGroupRemovedEvent;
use Ergonode\Attribute\Domain\Event\AttributeOptionAddedEvent;
use Ergonode\Attribute\Domain\Event\AttributeOptionChangedEvent;
use Ergonode\Attribute\Domain\Event\AttributeOptionRemovedEvent;
use Ergonode\Attribute\Domain\Event\Group\AttributeGroupCreatedEvent;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 */
class AttributeDomainEventSubscriber implements EventSubscriberInterface
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
            AttributeCreatedEvent::class => 'projection',
            AttributeLabelChangedEvent::class => 'projection',
            AttributeHintChangedEvent::class => 'projection',
            AttributePlaceholderChangedEvent::class => 'projection',
            AttributeGroupAddedEvent::class => 'projection',
            AttributeGroupRemovedEvent::class => 'projection',
            AttributeOptionAddedEvent::class => 'projection',
            AttributeOptionChangedEvent::class => 'projection',
            AttributeOptionRemovedEvent::class => 'projection',
            AttributeGroupCreatedEvent::class => 'projection',
            AttributeParameterChangeEvent::class => 'projection',
            AttributeArrayParameterChangeEvent::class => 'projection',
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
