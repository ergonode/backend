<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Infrastructure\Sender;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Notification\Domain\NotificationInterface;

interface NotificationStrategyInterface
{
    /**
     * @param UserId[] $recipients
     */
    public function send(NotificationInterface $notification, array $recipients): void;
}
