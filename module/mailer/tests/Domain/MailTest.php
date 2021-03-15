<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Tests\Domain;

use Ergonode\Mailer\Domain\Mail;
use Ergonode\Mailer\Domain\Recipient;
use Ergonode\Mailer\Domain\Sender;
use Ergonode\Mailer\Domain\Template;
use PHPUnit\Framework\TestCase;

final class MailTest extends TestCase
{
    public function testConstruct(): void
    {
        $recipient = $this->createMock(Recipient::class);
        $sender = $this->createMock(Sender::class);
        $template = $this->createMock(Template::class);
        $subject = 'ergonode';
        $mail = new Mail($recipient, $sender, $template, $subject);

        $this->assertSame($recipient, $mail->getRecipient());
        $this->assertSame($sender, $mail->getSender());
        $this->assertSame($template, $mail->getTemplate());
        $this->assertSame($subject, $mail->getSubject());
    }
}
