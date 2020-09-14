<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Mailer\Domain\MailMessageInterface;

/**
 */
class SendMailCommand implements DomainCommandInterface
{
    /**
     * @var MailMessageInterface
     */
    private MailMessageInterface $message;

    /**
     * @param MailMessageInterface $message
     */
    public function __construct(MailMessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * @return MailMessageInterface
     */
    public function getMessage(): MailMessageInterface
    {
        return $this->message;
    }
}
