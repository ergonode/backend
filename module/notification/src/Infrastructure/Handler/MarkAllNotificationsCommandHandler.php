<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Infrastructure\Handler;

use Ergonode\Notification\Domain\Query\NotificationQueryInterface;
use Ergonode\Notification\Domain\Command\MarkAllNotificationsCommand;

class MarkAllNotificationsCommandHandler
{
    private NotificationQueryInterface $query;

    public function __construct(NotificationQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(MarkAllNotificationsCommand $command): void
    {
        $this->query->markAll($command->getUserId(), $command->getReadAt());
    }
}
