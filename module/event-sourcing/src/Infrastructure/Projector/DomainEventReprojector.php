<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Projector;

use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\EventSourcing\Infrastructure\DomainEventStorageInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventReprojectorInterface;

class DomainEventReprojector implements DomainEventReprojectorInterface
{
    private ProjectorProvider $provider;

    private DomainEventStorageInterface $storage;

    public function __construct(ProjectorProvider $provider, DomainEventStorageInterface $storage)
    {
        $this->provider = $provider;
        $this->storage = $storage;
    }

    public function reproject(AggregateId $aggregateId, array $eventClasses = []): void
    {
        $envelopes = $this->storage->load($aggregateId);

        foreach ($envelopes as $envelope) {
            $event = $envelope->getEvent();
            $eventClass = get_class($event);
            if (in_array($eventClass, $eventClasses, true)) {
                $projectors = $this->provider->provide($event);
                foreach ($projectors as $projector) {
                    $projector($event);
                }
            }
        }
    }
}
