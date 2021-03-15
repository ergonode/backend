<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Domain;

use Ergonode\SharedKernel\Domain\Collection\EmailCollection;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

class Recipient
{
    private EmailCollection $to;

    private EmailCollection $bcc;

    private EmailCollection $cc;

    public function __construct(EmailCollection $to)
    {
        $this->to = $to;
        $this->bcc = new EmailCollection();
        $this->cc = new EmailCollection();
    }

    public function getTo(): EmailCollection
    {
        return $this->to;
    }

    public function addTo(Email $email): void
    {
        $this->to->add($email);
    }

    public function hasTo(): bool
    {
        return !$this->to->isEmpty();
    }

    public function getBcc(): EmailCollection
    {
        return $this->bcc;
    }

    public function addBcc(Email $email): void
    {
        $this->bcc->add($email);
    }

    public function hasBcc(): bool
    {
        return !$this->bcc->isEmpty();
    }

    public function getCc(): EmailCollection
    {
        return $this->cc;
    }

    public function addCc(Email $email): void
    {
        $this->cc->add($email);
    }

    public function hasCc(): bool
    {
        return !$this->cc->isEmpty();
    }
}
