<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Projector;

use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;

/**
 */
class DomainEventProjector
{
    /**
     * @var DomainEventProjectorInterface[]
     */
    private $projectors;

    /**
     * @param DomainEventProjectorInterface ...$projectors
     */
    public function __construct(DomainEventProjectorInterface ...$projectors)
    {
        $this->projectors = $projectors;
    }

    /**
     * @param DomainEventEnvelope $envelope
     *
     * @throws \Exception
     */
    public function projection(DomainEventEnvelope $envelope): void
    {
        foreach ($this->projectors as $projector) {
            if ($projector->support($envelope->getEvent())) {
                $projector->projection($envelope->getAggregateId(), $envelope->getEvent());
            }
        }
    }
}
