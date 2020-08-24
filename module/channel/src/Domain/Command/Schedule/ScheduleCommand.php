<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Command\Schedule;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class ScheduleCommand implements DomainCommandInterface
{
    /**
     * @var \DateTime
     */
    private \DateTime $date;

    /**
     * @param \DateTime $date
     */
    public function __construct(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }
}
