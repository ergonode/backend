<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Persistence\Dbal\Repository\Mapper;

use Ergonode\Channel\Domain\Entity\Scheduler;

/**
 */
class SchedulerMapper
{
    /**
     * @param Scheduler $scheduler
     *
     * @return array
     */
    public function map(Scheduler $scheduler): array
    {
        return [
            'id' => $scheduler->getId()->getValue(),
            'active' => $scheduler->isActive(),
            'start' => $scheduler->getStart() ? $scheduler->getStart()->format('Y-m-d H:i:s') : null,
            'hour' => $scheduler->getHour(),
            'minute' => $scheduler->getMinute(),
        ];
    }
}
