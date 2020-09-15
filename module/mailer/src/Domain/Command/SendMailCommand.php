<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Mailer\Domain\MailInterface;

/**
 */
class SendMailCommand implements DomainCommandInterface
{
    /**
     * @var MailInterface
     */
    private MailInterface $mail;

    /**
     * @param MailInterface $mail
     */
    public function __construct(MailInterface $mail)
    {
        $this->mail = $mail;
    }

    /**
     * @return MailInterface
     */
    public function getMail(): MailInterface
    {
        return $this->mail;
    }
}
