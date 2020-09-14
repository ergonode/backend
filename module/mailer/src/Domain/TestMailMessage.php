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
 * Test mail message
 */
class TestMailMessage extends MailMessage
{
    private const TEMPLATE = '@ErgonodeMailer/message/test.html.twig';

    /**
     * @param Email    $to
     * @param Language $language
     */
    public function __construct(Email $to, Language $language)
    {
        $this->to = $to;
        $this->language = $language;
        $this->subject = 'Ergonode test message';
        $this->createdAt = new DateTime();
        $this->parameters = [
            'date' => $this->createdAt,
        ];
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return self::TEMPLATE;
    }
}