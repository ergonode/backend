<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\EventSubscriber;

use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjector;
use Ergonode\Product\Domain\Event\ProductAddedToCategory;
use Ergonode\Product\Domain\Event\ProductCreated;
use Ergonode\Product\Domain\Event\ProductRemovedFromCategory;
use Ergonode\Product\Domain\Event\ProductValueAdded;
use Ergonode\Product\Domain\Event\ProductValueChanged;
use Ergonode\Product\Domain\Event\ProductValueRemoved;
use Ergonode\Product\Domain\Event\ProductVersionIncreased;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 */
class ProductDomainEventSubscriber implements EventSubscriberInterface
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
            ProductCreated::class => 'projection',
            ProductVersionIncreased::class => 'projection',
            ProductValueAdded::class => 'projection',
            ProductValueChanged::class => 'projection',
            ProductValueRemoved::class => 'projection',
            ProductAddedToCategory::class => 'projection',
            ProductRemovedFromCategory::class => 'projection',
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
