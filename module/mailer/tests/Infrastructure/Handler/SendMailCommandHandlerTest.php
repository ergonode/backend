<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Tests\Infrastructure\Handler;

use Ergonode\Mailer\Domain\Command\SendMailCommand;
use Ergonode\Mailer\Infrastructure\Handler\SendMailCommandHandler;
use Ergonode\Mailer\Infrastructure\Sender\MailerSender;
use PHPUnit\Framework\TestCase;

final class SendMailCommandHandlerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testHandling(): void
    {
        $command = $this->createMock(SendMailCommand::class);

        $mailerSender = $this->createMock(MailerSender::class);
        $mailerSender->expects($this->once())->method('send');

        $handler = new SendMailCommandHandler($mailerSender);
        $handler->__invoke($command);
    }
}
