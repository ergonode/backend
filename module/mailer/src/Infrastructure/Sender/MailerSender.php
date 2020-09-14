<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Mailer\Infrastructure\Sender;

use Ergonode\Mailer\Domain\MailMessageInterface;

/**
 */
class MailerSender
{
    /**
     * @var MailerStrategyInterface[]
     */
    private array $strategies;

    /**
     * @param MailerStrategyInterface ...$strategies
     */
    public function __construct(MailerStrategyInterface...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param MailMessageInterface $message
     */
    public function send(MailMessageInterface $message): void
    {
        foreach ($this->strategies as $strategy) {
            $strategy->send($message);
        }
    }
}
