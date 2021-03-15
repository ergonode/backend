<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Domain;

use Ergonode\SharedKernel\Domain\Collection\EmailCollection;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

class Sender
{
    private EmailCollection $from;

    private EmailCollection $replyTo;

    public function __construct()
    {
        $this->from = new EmailCollection();
        $this->replyTo = new EmailCollection();
    }

    public function getFrom(): EmailCollection
    {
        return $this->from;
    }

    public function addFrom(Email $email): void
    {
        $this->from->add($email);
    }

    public function hasFrom(): bool
    {
        return !$this->from->isEmpty();
    }

    public function getReplyTo(): EmailCollection
    {
        return $this->replyTo;
    }

    public function addReplyTo(Email $email): void
    {
        $this->replyTo->add($email);
    }

    public function hasReplyTo(): bool
    {
        return !$this->replyTo->isEmpty();
    }
}
