<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Command\Schedule;

use Ergonode\Channel\Domain\Command\ChannelCommandInterface;

class ScheduleCommand implements ChannelCommandInterface
{
    private \DateTime $date;

    public function __construct(\DateTime $date)
    {
        $this->date = $date;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }
}
