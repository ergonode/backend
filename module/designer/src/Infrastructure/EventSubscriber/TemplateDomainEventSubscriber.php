<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\EventSubscriber;

use Ergonode\Designer\Domain\Event\TemplateElementChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateImageAddedEvent;
use Ergonode\Designer\Domain\Event\TemplateImageChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateImageRemovedEvent;
use Ergonode\Designer\Domain\Event\TemplateRemovedEvent;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjector;
use Ergonode\Designer\Domain\Event\Group\TemplateGroupCreatedEvent;
use Ergonode\Designer\Domain\Event\TemplateCreatedEvent;
use Ergonode\Designer\Domain\Event\TemplateElementAddedEvent;
use Ergonode\Designer\Domain\Event\TemplateElementRemovedEvent;
use Ergonode\Designer\Domain\Event\TemplateGroupChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateNameChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateSectionAddedEvent;
use Ergonode\Designer\Domain\Event\TemplateSectionChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateSectionRemovedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 */
class TemplateDomainEventSubscriber implements EventSubscriberInterface
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
            TemplateCreatedEvent::class => 'projection',
            TemplateRemovedEvent::class => 'projection',
            TemplateNameChangedEvent::class => 'projection',
            TemplateGroupChangedEvent::class => 'projection',
            TemplateElementAddedEvent::class => 'projection',
            TemplateElementChangedEvent::class => 'projection',
            TemplateElementRemovedEvent::class => 'projection',
            TemplateSectionAddedEvent::class => 'projection',
            TemplateSectionChangedEvent::class => 'projection',
            TemplateSectionRemovedEvent::class => 'projection',
            TemplateGroupCreatedEvent::class => 'projection',
            TemplateImageAddedEvent::class => 'projection',
            TemplateImageChangedEvent::class => 'projection',
            TemplateImageRemovedEvent::class => 'projection',
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
