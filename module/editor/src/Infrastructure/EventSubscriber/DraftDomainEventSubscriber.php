<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Infrastructure\EventSubscriber;

use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjector;
use Ergonode\Editor\Domain\Event\ProductDraftApplied;
use Ergonode\Editor\Domain\Event\ProductDraftCreated;
use Ergonode\Editor\Domain\Event\ProductDraftValueAdded;
use Ergonode\Editor\Domain\Event\ProductDraftValueChanged;
use Ergonode\Editor\Domain\Event\ProductDraftValueRemoved;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 */
class DraftDomainEventSubscriber implements EventSubscriberInterface
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
            ProductDraftCreated::class => 'projection',
            ProductDraftApplied::class => 'projection',
            ProductDraftValueAdded::class => 'projection',
            ProductDraftValueChanged::class => 'projection',
            ProductDraftValueRemoved::class => 'projection',
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
