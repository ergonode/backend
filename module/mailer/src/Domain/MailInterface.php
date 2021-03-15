<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Domain;

use DateTime;

interface MailInterface
{
    public function getRecipient(): Recipient;

    public function getSender(): Sender;

    public function getSubject(): string;

    public function getTemplate(): Template;

    public function getCreatedAt(): DateTime;
}
