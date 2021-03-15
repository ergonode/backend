<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Infrastructure\Sender;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Notification\Domain\NotificationInterface;
use Webmozart\Assert\Assert;

class NotificationSender
{
    /**
     * @var NotificationStrategyInterface[]
     */
    private array $strategies;

    public function __construct(NotificationStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param array $recipients
     */
    public function send(NotificationInterface $notification, array $recipients): void
    {
        Assert::allIsInstanceOf($recipients, UserId::class);

        foreach ($this->strategies as $strategy) {
            $strategy->send($notification, $recipients);
        }
    }
}
