<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Tests\Domain;

use Ergonode\Mailer\Domain\Recipient;
use Ergonode\SharedKernel\Domain\Collection\EmailCollection;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

final class RecipientTest extends TestCase
{
    public function testConstructor(): void
    {
        $email = new Email('test@ergonode.com');
        $to = new EmailCollection([$email]);

        $recipient = new Recipient($to);

        $this->assertSame($to, $recipient->getTo());
        $this->assertTrue($recipient->hasTo());
        $this->assertCount(1, $recipient->getTo());
        $this->assertFalse($recipient->hasBcc());
        $this->assertCount(0, $recipient->getBcc());
        $this->assertFalse($recipient->hasCc());
        $this->assertCount(0, $recipient->getCc());
    }

    public function testAdd(): void
    {
        $email = new Email('test@ergonode.com');
        $to = new EmailCollection([$email]);
        $email2 = new Email('test2@ergonode.com');

        $recipient = new Recipient($to);
        $recipient->addTo($email2);
        $recipient->addCc($email2);
        $recipient->addBcc($email2);

        $this->assertCount(2, $recipient->getTo());
        $this->assertCount(1, $recipient->getCc());
        $this->assertCount(1, $recipient->getBcc());
    }
}
