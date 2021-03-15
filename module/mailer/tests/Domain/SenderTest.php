<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Tests\Domain;

use Ergonode\Mailer\Domain\Sender;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

final class SenderTest extends TestCase
{
    public function testConstructor(): void
    {
        $sender = new Sender();

        $this->assertFalse($sender->hasFrom());
        $this->assertCount(0, $sender->getFrom());
        $this->assertFalse($sender->hasReplyTo());
        $this->assertCount(0, $sender->getReplyTo());
    }

    public function testAdd(): void
    {
        $email = new Email('test@ergonode.com');

        $sender = new Sender();
        $sender->addFrom($email);
        $sender->addReplyTo($email);

        $this->assertTrue($sender->hasFrom());
        $this->assertCount(1, $sender->getFrom());
        $this->assertTrue($sender->hasReplyTo());
        $this->assertCount(1, $sender->getReplyTo());
    }
}
