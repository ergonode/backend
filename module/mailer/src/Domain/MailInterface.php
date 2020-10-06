<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Mailer\Domain;

use DateTime;

/**
 */
interface MailInterface
{
    /**
     * @return Recipient
     */
    public function getRecipient(): Recipient;

    /**
     * @return Sender
     */
    public function getSender(): Sender;

    /**
     * @return string
     */
    public function getSubject(): string;

    /**
     * @return Template
     */
    public function getTemplate(): Template;

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime;
}
