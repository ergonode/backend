<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Mail;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Mailer\Domain\Mail;
use Ergonode\Mailer\Domain\Recipient;
use Ergonode\Mailer\Domain\Sender;
use Ergonode\Mailer\Domain\Template;
use Ergonode\SharedKernel\Domain\Collection\EmailCollection;

class UserPasswordChangedMail extends Mail
{
    public function __construct(User $user)
    {
        $recipient = new Recipient(new EmailCollection([$user->getEmail()]));
        $template = new Template(
            '@ErgonodeAccount/message/password_changed.html.twig',
            $user->getLanguage(),
            [
                'firstName' => $user->getFirstName(),
            ]
        );

        parent::__construct($recipient, new Sender(), $template, 'You’ve changed your password at Ergonode PIM!');
    }
}
