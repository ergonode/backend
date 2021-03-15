<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Infrastructure\Sender;

use Ergonode\Mailer\Domain\MailInterface;
use Webmozart\Assert\Assert;

class MailerSender
{
    /**
     * @var MailerStrategyInterface[]
     */
    private iterable $strategies;

    /**
     * @param iterable $strategies
     */
    public function __construct(iterable $strategies)
    {
        Assert::allIsInstanceOf($strategies, MailerStrategyInterface::class);
        $this->strategies = $strategies;
    }

    public function send(MailInterface $message): void
    {
        foreach ($this->strategies as $strategy) {
            $strategy->send($message);
        }
    }
}
