<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Mailer\Infrastructure\Sender;

use Ergonode\Mailer\Domain\MailInterface;

interface MailerStrategyInterface
{
    public function send(MailInterface $mail): void;
}
