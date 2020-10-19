<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Tests\Domain;

use Ergonode\Mailer\Domain\Mail;
use Ergonode\Mailer\Domain\Recipient;
use Ergonode\Mailer\Domain\Sender;
use Ergonode\Mailer\Domain\Template;
use PHPUnit\Framework\TestCase;

/**
 */
final class MailTest extends TestCase
{
    /**
     */
    public function testConstruct(): void
    {
        $recipient = $this->createMock(Recipient::class);
        $sender = $this->createMock(Sender::class);
        $template = $this->createMock(Template::class);
        $subject = 'ergonode';
        $mail = new Mail($recipient, $sender, $template, $subject);

        self::assertSame($recipient, $mail->getRecipient());
        self::assertSame($sender, $mail->getSender());
        self::assertSame($template, $mail->getTemplate());
        self::assertSame($subject, $mail->getSubject());
    }
}
