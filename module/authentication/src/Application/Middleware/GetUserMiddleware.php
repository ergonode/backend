<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Middleware;

use Ergonode\Account\Application\Security\Security;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Authentication\Application\Stamp\UserStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

class GetUserMiddleware implements MiddlewareInterface
{

    private Security $security;

    public function __construct(
        Security $security
    ) {
        $this->security = $security;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (!$envelope->last(ReceivedStamp::class)) {
            /** @var User $user */
            $user = $this->security->getUser();

            if ($user) {
                $envelope = $envelope->with(new UserStamp($user->getId()));
            }
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
