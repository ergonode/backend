<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Repository;

use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

/**
 */
interface ChannelRepositoryInterface
{
    /**
     * @param ChannelId $id
     *
     * @return AbstractAggregateRoot|Channel
     */
    public function load(ChannelId $id): ?AbstractAggregateRoot;

    /**
     * {@inheritDoc}
     */
    public function exists(ChannelId $id) : bool;

    /**
     * @param Channel $aggregateRoot
     */
    public function save(Channel $aggregateRoot): void;

    /**
     * @param Channel $aggregateRoot
     */
    public function delete(Channel $aggregateRoot): void;
}
