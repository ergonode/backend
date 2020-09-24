<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Channel\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
interface SchedulerQueryInterface
{
    /**
     * @param \DateTime $time
     *
     * @return ChannelId[]
     */
    public function getReadyToRun(\DateTime $time): array;

    /**
     * @param ChannelId $id
     * @param \DateTime $time
     */
    public function markAsRun(ChannelId $id, \DateTime $time): void;
}
