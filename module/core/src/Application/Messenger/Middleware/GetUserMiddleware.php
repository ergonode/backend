<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Messenger\Middleware;

use Ergonode\Core\Application\Messenger\Stamp\UserStamp;
use Ergonode\Core\Application\Security\Security;
use Ergonode\Core\Application\Security\User\CachedUser;
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
            $user = $this->security->getUser();

            if ($user) {
                $envelope = $envelope->with(
                    new UserStamp(
                        CachedUser::createFromUser($user),
                    ),
                );
            }
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
