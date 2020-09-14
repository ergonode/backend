<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Infrastructure\Sender;

use Ergonode\Mailer\Domain\MailMessageInterface;

interface MailerStrategyInterface
{
    /**
     * @param MailMessageInterface $message
     */
    public function send(MailMessageInterface $message): void;
}