<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\Channel\Domain\Entity\Scheduler;

class DbalSchedulerMapper
{
    /**
     * @return array
     */
    public function map(Scheduler $scheduler): array
    {
        return [
            'id' => $scheduler->getId()->getValue(),
            'active' => $scheduler->isActive(),
            'start' => $scheduler->getStart(),
            'hour' => $scheduler->getHour(),
            'minute' => $scheduler->getMinute(),
        ];
    }
}
