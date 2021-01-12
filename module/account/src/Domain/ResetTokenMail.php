<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain;

use Ergonode\Account\Domain\ValueObject\ResetToken;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Mailer\Domain\Mail;
use Ergonode\Mailer\Domain\Recipient;
use Ergonode\Mailer\Domain\Sender;
use Ergonode\Mailer\Domain\Template;
use Ergonode\SharedKernel\Domain\Collection\EmailCollection;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

class ResetTokenMail extends Mail
{
    public function __construct(Email $to, Language $language, ResetToken $token, string $url)
    {
        $recipient = new Recipient(new EmailCollection([$to]));
        $template = new Template(
            '@ErgonodeAccount/message/token.html.twig',
            $language,
            [
                'url' => $url,
                'token' => $token->getValue(),
            ]
        );

        parent::__construct($recipient, new Sender(), $template, 'Ergonode reset Token');
    }
}
