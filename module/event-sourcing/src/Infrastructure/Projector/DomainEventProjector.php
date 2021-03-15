<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Projector;

use Ergonode\SharedKernel\Domain\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventProjectorInterface;

class DomainEventProjector implements DomainEventProjectorInterface
{
    private ProjectorProvider $provider;

    public function __construct(ProjectorProvider $provider)
    {
        $this->provider = $provider;
    }

    public function project(DomainEventInterface $event): void
    {
        $projectors = $this->provider->provide($event);
        foreach ($projectors as $projector) {
            $projector($event);
        }
    }
}
