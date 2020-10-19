<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Tests\Domain;

use Ergonode\Mailer\Domain\Sender;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

/**
 */
final class SenderTest extends TestCase
{
    /**
     */
    public function testConstructor(): void
    {
        $sender = new Sender();

        self::assertFalse($sender->hasFrom());
        self::assertCount(0, $sender->getFrom());
        self::assertFalse($sender->hasReplyTo());
        self::assertCount(0, $sender->getReplyTo());
    }

    /**
     */
    public function testAdd(): void
    {
        $email = new Email('test@ergonode.com');

        $sender = new Sender();
        $sender->addFrom($email);
        $sender->addReplyTo($email);

        self::assertTrue($sender->hasFrom());
        self::assertCount(1, $sender->getFrom());
        self::assertTrue($sender->hasReplyTo());
        self::assertCount(1, $sender->getReplyTo());
    }
}
