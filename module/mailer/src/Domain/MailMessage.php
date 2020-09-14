<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Domain;

use DateTime;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

/**
 * Abstract class for mail message
 */
abstract class AbstractMailMessage implements MailMessageInterface
{
    /**
     * @var Email
     */
    protected Email $to;

    /**
     * @var Language
     */
    protected Language $language;

    /**
     * @var Email|null
     */
    protected ?Email $from = null;

    /**
     * @var Email|null
     */
    protected ?Email $replyTo = null;

    /**
     * @var string
     */
    protected string $subject;

    /**
     * @var string[]
     */
    protected array $parameters = [];

    /**
     * @var DateTime
     */
    protected DateTime $createdAt;

    /**
     * {@inheritdoc}
     */
    public function getTo(): Email
    {
        return $this->to;
    }

    /**
     * {@inheritdoc}
     */
    public function getFrom(): ?Email
    {
        return $this->from;
    }

    /**
     * {@inheritdoc}
     */
    public function hasFrom(): bool
    {
        return null !== $this->from;
    }

    /**
     * {@inheritdoc}
     */
    public function setFrom(?Email $from = null): void
    {
        $this->from = $from;
    }

    /**
     * {@inheritdoc}
     */
    public function getReplyTo(): ?Email
    {
        return $this->replyTo;
    }

    /**
     * {@inheritdoc}
     */
    public function hasReplyTo(): bool
    {
        return null !== $this->replyTo;
    }

    /**
     * {@inheritdoc}
     */
    public function setReplyTo(?Email $replyTo = null): void
    {
        $this->replyTo = $replyTo;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
