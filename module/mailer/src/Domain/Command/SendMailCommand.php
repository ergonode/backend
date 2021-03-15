<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Domain\Command;

use Ergonode\Mailer\Domain\MailInterface;

class SendMailCommand implements MailerCommandInterface
{
    private MailInterface $mail;

    public function __construct(MailInterface $mail)
    {
        $this->mail = $mail;
    }

    public function getMail(): MailInterface
    {
        return $this->mail;
    }
}
