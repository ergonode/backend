<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
    /**
     * @param Email    $to
     * @param Language $language
     */
    public function __construct(Email $to, Language $language)
    {
        $this->createdAt = new DateTime();
        $this->recipient = new Recipient(new EmailCollection([$to]));
        $this->sender = new Sender();
        $this->template = new Template(
            '@ErgonodeMailer/message/test.html.twig',
            $language,
            [
                'date' => $this->createdAt,
            ]
        );
        $this->subject = 'Ergonode test message';
    }
}
