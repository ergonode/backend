<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Middleware;

use Ergonode\Authentication\Application\Stamp\UserStamp;
use Ergonode\Authentication\Application\Token\UserToken;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{

    private TokenStorageInterface $tokenStorage;

    public function __construct(
        TokenStorageInterface $tokenStorage
    ) {
        $this->tokenStorage = $tokenStorage;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (!$envelope->last(ReceivedStamp::class)) {
            return $stack->next()->handle($envelope, $stack);
        }
        $this->tokenStorage->setToken(null);
        /** @var UserStamp $stamp */
        $stamp = $envelope->last(UserStamp::class);

        if ($stamp) {
            $token = new UserToken($stamp->getUser(), []);
            $this->tokenStorage->setToken($token);
        }
        try {
            $envelope = $stack->next()->handle($envelope, $stack);
        } catch (\Throwable $exception) {
            $this->tokenStorage->setToken(null);
            throw $exception;
        }

        $this->tokenStorage->setToken(null);

        return $envelope;
    }
}
