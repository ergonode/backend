<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Mail;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\ValueObject\ResetToken;
use Ergonode\Mailer\Domain\Mail;
use Ergonode\Mailer\Domain\Recipient;
use Ergonode\Mailer\Domain\Sender;
use Ergonode\Mailer\Domain\Template;
use Ergonode\SharedKernel\Domain\Collection\EmailCollection;

class ResetTokenMail extends Mail
{
    public function __construct(User $user, ResetToken $token, string $url)
    {
        $recipient = new Recipient(new EmailCollection([$user->getEmail()]));
        $template = new Template(
            '@ErgonodeAccount/message/token.html.twig',
            $user->getLanguage(),
            [
                'url' => $url,
                'token' => $token->getValue(),
                'firstName' => $user->getFirstName(),
            ]
        );

        parent::__construct($recipient, new Sender(), $template, 'Reset your password at Ergonode PIM');
    }
}
