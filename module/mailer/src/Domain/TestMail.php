<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Domain;

use DateTime;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Collection\EmailCollection;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

/**
 * Test mail message
 */
class TestMail extends Mail
{
    public function __construct(Email $to, Language $language)
    {
        $recipient = new Recipient(new EmailCollection([$to]));
        $template = new Template(
            '@ErgonodeMailer/message/test.html.twig',
            $language,
            [
                'date' => new DateTime(),
            ]
        );

        parent::__construct($recipient, new Sender(), $template, 'Ergonode test message');
    }
}
