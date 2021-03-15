<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Projector;

use Ergonode\SharedKernel\Domain\DomainEventInterface;

class ProjectorProvider
{
    private iterable $collection = [];

    public function add(object $projector, string $event): void
    {
        if (!is_callable($projector)) {
            throw new \InvalidArgumentException(sprintf('Projector %s is not callable', get_class($projector)));
        }

        $this->collection[$event][get_class($projector)] = $projector;
    }

    /**
     * @return callable[]
     */
    public function provide(DomainEventInterface $event): array
    {
        $class = get_class($event);
        if (array_key_exists($class, $this->collection)) {
            return $this->collection[$class];
        }

        return [];
    }
}
