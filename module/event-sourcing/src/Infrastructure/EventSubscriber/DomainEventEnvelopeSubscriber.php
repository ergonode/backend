<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\EventSubscriber;

use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\Exception\ProjectorException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 */
class DomainEventEnvelopeSubscriber implements EventSubscriberInterface
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
            DomainEventEnvelope::class => 'projection',
        ];
    }

    /**
     * @param DomainEventEnvelope $envelope
     *
     * @throws \Exception
     */
    public function projection(DomainEventEnvelope $envelope): void
    {
        try {
            $this->projector->projection($envelope);
        } catch (\Throwable $exception) {
            throw new ProjectorException($envelope->getEvent(), $exception);
        }
    }
}
