<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Domain;

use DateTime;

class Mail implements MailInterface
{
    protected Recipient $recipient;

    protected Sender $sender;

    protected Template $template;

    protected string $subject;

    protected DateTime $createdAt;

    public function __construct(
        Recipient $recipient,
        Sender $sender,
        Template $template,
        string $subject
    ) {
        $this->recipient = $recipient;
        $this->sender = $sender;
        $this->template = $template;
        $this->subject = $subject;
        $this->createdAt = new DateTime();
    }

    /**
     * {@inheritDoc}
     */
    public function getRecipient(): Recipient
    {
        return $this->recipient;
    }

    /**
     * {@inheritDoc}
     */
    public function getSender(): Sender
    {
        return $this->sender;
    }

    /**
     * {@inheritDoc}
     */
    public function getTemplate(): Template
    {
        return $this->template;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
