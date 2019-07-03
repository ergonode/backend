<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Infrastructure\EventSubscriber;

use Ergonode\CategoryTree\Domain\Event\CategoryTreeCategoriesChangedEvent;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjector;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCategoryAddedEvent;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 */
class CategoryTreeDomainEventSubscriber implements EventSubscriberInterface
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
            CategoryTreeCreatedEvent::class => 'projection',
            CategoryTreeCategoryAddedEvent::class => 'projection',
            CategoryTreeCategoriesChangedEvent::class => 'projection',
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
