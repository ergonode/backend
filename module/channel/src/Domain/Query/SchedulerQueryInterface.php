<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface SchedulerQueryInterface
{
    /**
     * @return ChannelId[]
     */
    public function getReadyToRun(\DateTime $time): array;

    public function markAsRun(ChannelId $id, \DateTime $time): void;
}
