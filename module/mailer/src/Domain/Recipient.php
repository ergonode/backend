<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Domain;

use Ergonode\SharedKernel\Domain\Collection\EmailCollection;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

class Recipient
{
    /**
     * @var EmailCollection
     */
    private EmailCollection $to;

    /**
     * @var EmailCollection
     */
    private EmailCollection $bcc;

    /**
     * @var EmailCollection
     */
    private EmailCollection $cc;

    /**
     * @param EmailCollection $to
     */
    public function __construct(EmailCollection $to)
    {
        $this->to = $to;
        $this->bcc = new EmailCollection();
        $this->cc = new EmailCollection();
    }

    /**
     * @return EmailCollection
     */
    public function getTo(): EmailCollection
    {
        return $this->to;
    }

    /**
     * @param Email $email
     */
    public function addTo(Email $email): void
    {
        $this->to->add($email);
    }

    /**
     * @return bool
     */
    public function hasTo(): bool
    {
        return !$this->to->isEmpty();
    }

    /**
     * @return EmailCollection
     */
    public function getBcc(): EmailCollection
    {
        return $this->bcc;
    }

    /**
     * @param Email $email
     */
    public function addBcc(Email $email): void
    {
        $this->bcc->add($email);
    }

    /**
     * @return bool
     */
    public function hasBcc(): bool
    {
        return !$this->bcc->isEmpty();
    }

    /**
     * @return EmailCollection
     */
    public function getCc(): EmailCollection
    {
        return $this->cc;
    }

    /**
     * @param Email $email
     */
    public function addCc(Email $email): void
    {
        $this->cc->add($email);
    }

    /**
     * @return bool
     */
    public function hasCc(): bool
    {
        return !$this->cc->isEmpty();
    }
}
