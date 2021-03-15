<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Tests\Infrastructure\Sender;

use Ergonode\Mailer\Domain\MailInterface;
use Ergonode\Mailer\Infrastructure\Sender\MailerSender;
use Ergonode\Mailer\Infrastructure\Sender\MailerStrategyInterface;
use PHPUnit\Framework\TestCase;

final class MailerSenderTest extends TestCase
{
    public function testHandling(): void
    {
        $strategy = $this->createMock(MailerStrategyInterface::class);
        $strategy->expects($this->once())->method('send');

        $message = $this->createMock(MailInterface::class);

        $handler = new MailerSender([$strategy]);
        $handler->send($message);
    }
}
