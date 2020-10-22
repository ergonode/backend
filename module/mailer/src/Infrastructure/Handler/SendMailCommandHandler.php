<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Infrastructure\Handler;

use Ergonode\Mailer\Domain\Command\SendMailCommand;
use Ergonode\Mailer\Infrastructure\Sender\MailerSender;

class SendMailCommandHandler
{
    /**
     * @var MailerSender
     */
    private MailerSender $mailerSender;

    /**
     * @param MailerSender $mailerSender
     */
    public function __construct(MailerSender $mailerSender)
    {
        $this->mailerSender = $mailerSender;
    }

    /**
     * @param SendMailCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(SendMailCommand $command)
    {
        $this->mailerSender->send($command->getMail());
    }
}
