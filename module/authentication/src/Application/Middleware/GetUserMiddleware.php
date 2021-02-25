<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Middleware;

use Ergonode\Account\Application\Security\Security;
use Ergonode\Authentication\Application\Security\User\CachedUser;
use Ergonode\Authentication\Application\Stamp\UserStamp;
use Ergonode\Core\Domain\User\AggregateUserInterface;
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

            if ($user instanceof AggregateUserInterface) {
                $envelope = $envelope->with(
                    new UserStamp(
                        CachedUser::createFromAggregate($user),
                    ),
                );
            }
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
