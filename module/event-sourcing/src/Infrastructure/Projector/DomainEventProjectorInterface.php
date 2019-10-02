<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\EventSourcing\Infrastructure\Projector;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;

/**
 */
interface DomainEventProjectorInterface
{
    /**
     * @param DomainEventInterface $event
     *
     * @return bool
     */
    public function supports(DomainEventInterface $event): bool;

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws \Exception
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void;
}
