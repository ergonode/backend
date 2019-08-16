<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\EventSubscriber;

use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjector;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusAddedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusChangedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusRemovedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 */
class WorkflowDomainEventSubscriber implements EventSubscriberInterface
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
            WorkflowStatusAddedEvent::class => 'projection',
            WorkflowStatusChangedEvent::class => 'projection',
            WorkflowStatusRemovedEvent::class => 'projection',
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
