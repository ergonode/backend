<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Mailer\Domain;

use DateTime;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

/**
 */
interface MailMessageInterface
{
    /**
     * @return Email
     */
    public function getTo(): Email;

    /**
     * @return Email|null
     */
    public function getFrom(): ?Email;

    /**
     * @return bool
     */
    public function hasFrom(): bool;

    /**
     * @param Email|null $from
     */
    public function setFrom(?Email $from = null): void;

    /**
     * @return Email|null
     */
    public function getReplyTo(): ?Email;

    /**
     * @return bool
     */
    public function hasReplyTo(): bool;

    /**
     * @param Email|null $replyTo Default null
     */
    public function setReplyTo(Email $replyTo = null): void;

    /**
     * @return string
     */
    public function getSubject(): string;

    /**
     * @return string
     */
    public function getTemplate(): string;

    /**
     * @return string[]
     */
    public function getParameters(): array;

    /**
     * @return Language
     */
    public function getLanguage(): Language;

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime;
}
