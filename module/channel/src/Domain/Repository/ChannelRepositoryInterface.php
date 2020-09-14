<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Repository;

use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
interface ChannelRepositoryInterface
{
    /**
     * @param ChannelId $id
     *
     * @return AbstractChannel
     */
    public function load(ChannelId $id): ?AbstractChannel;

    /**
     * @param ChannelId $id
     *
     * @return bool
     */
    public function exists(ChannelId $id) : bool;

    /**
     * @param AbstractChannel $aggregateRoot
     */
    public function save(AbstractChannel $aggregateRoot): void;

    /**
     * @param AbstractChannel $aggregateRoot
     */
    public function delete(AbstractChannel $aggregateRoot): void;
}
