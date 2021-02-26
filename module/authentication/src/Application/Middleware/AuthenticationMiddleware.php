<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Middleware;

use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Authentication\Application\Stamp\UserStamp;
use Ergonode\Authentication\Application\Token\UserToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{

    private TokenStorageInterface $tokenStorage;

    private UserRepositoryInterface $userRepository;

    private LoggerInterface $logger;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserRepositoryInterface $userRepository,
        LoggerInterface $logger
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->userRepository = $userRepository;
        $this->logger = $logger;
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
            $user = $this->userRepository->load($stamp->getUserId());
            if ($user) {
                $roles = $user->getRoles();
                $token = new UserToken($user, $roles);
                $this->tokenStorage->setToken($token);
            } else {
                $this->logger->error(sprintf('Can\'t find user with id "%s"', $stamp->getUserId()));
            }
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
