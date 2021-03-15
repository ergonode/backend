<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Infrastructure\Handler;

use Ergonode\Mailer\Domain\Command\SendMailCommand;
use Ergonode\Mailer\Infrastructure\Sender\MailerSender;

class SendMailCommandHandler
{
    private MailerSender $mailerSender;

    public function __construct(MailerSender $mailerSender)
    {
        $this->mailerSender = $mailerSender;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(SendMailCommand $command): void
    {
        $this->mailerSender->send($command->getMail());
    }
}
