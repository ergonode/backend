<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;

/**
 */
interface DomainEventStoreInterface
{
    /**
     * @param AbstractId $id
     * @param string     $table
     *
     * @return DomainEventStream
     */
    public function load(AbstractId $id, ?string $table = null): DomainEventStream;

    /**
     * @param AbstractId        $id
     * @param DomainEventStream $stream
     * @param string            $table
     */
    public function append(AbstractId $id, DomainEventStream $stream, ?string $table = null): void;
}
