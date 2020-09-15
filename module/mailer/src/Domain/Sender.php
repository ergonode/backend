<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Domain;

use Ergonode\SharedKernel\Domain\Collection\EmailCollection;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

/**
 */
class Sender
{
    /**
     * @var EmailCollection
     */
    private EmailCollection $from;

    /**
     * @var EmailCollection
     */
    private EmailCollection $replyTo;

    /**
     */
    public function __construct()
    {
        $this->from = new EmailCollection();
        $this->replyTo = new EmailCollection();
    }

    /**
     * @return EmailCollection
     */
    public function getFrom(): EmailCollection
    {
        return $this->from;
    }

    /**
     * @param Email $email
     */
    public function addFrom(Email $email): void
    {
        $this->from->add($email);
    }

    /**
     * @return bool
     */
    public function hasFrom(): bool
    {
        return !$this->from->isEmpty();
    }

    /**
     * @return EmailCollection
     */
    public function getReplyTo(): EmailCollection
    {
        return $this->replyTo;
    }

    /**
     * @param Email $email
     */
    public function addReplyTo(Email $email): void
    {
        $this->replyTo->add($email);
    }

    /**
     * @return bool
     */
    public function hasReplyTo(): bool
    {
        return !$this->replyTo->isEmpty();
    }
}
