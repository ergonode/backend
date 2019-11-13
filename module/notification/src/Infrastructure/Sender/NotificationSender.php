<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Infrastructure\Sender;

use Ergonode\Account\Domain\Entity\UserId;
use Webmozart\Assert\Assert;

/**
 */
class NotificationSender
{
    /**
     * @var NotificationStrategyInterface[]
     */
    private $strategies;

    /**
     * @param NotificationStrategyInterface ...$strategies
     */
    public function __construct(NotificationStrategyInterface... $strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param UserId[]    $recipients
     * @param string      $message
     * @param UserId|null $author
     */
    public function send(array $recipients, string $message, ?UserId $author = null): void
    {
        Assert::allIsInstanceOf($recipients, UserId::class);

        foreach ($this->strategies as $strategy) {
            $strategy->send($recipients, $message, $author);
        }
    }
}
