<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Infrastructure\Handler;

use Ergonode\Notification\Domain\Command\MarkNotificationCommand;
use Ergonode\Notification\Domain\Query\NotificationQueryInterface;

class MarkNotificationCommandHandler
{
    private NotificationQueryInterface $query;

    public function __construct(NotificationQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(MarkNotificationCommand $command)
    {
        $this->query->mark($command->getNotificationId(), $command->getUserId(), $command->getReadAt());
    }
}
