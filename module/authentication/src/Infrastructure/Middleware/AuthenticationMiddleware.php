<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Infrastructure\Middleware;

use Ergonode\Account\Application\Security\Security;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\Authentication\Infrastructure\Stamp\UserStamp;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{

    private TokenStorageInterface $tokenStorage;

    private UserRepositoryInterface $userRepository;

    private Security $security;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserRepositoryInterface $userRepository,
        Security $security
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->userRepository = $userRepository;
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
            return $stack->next()->handle($envelope, $stack);
        }

        if ($envelope->last(ReceivedStamp::class)) {

            $this->tokenStorage->setToken(null);
            /** @var UserStamp $stamp */
            $stamp = $envelope->last(UserStamp::class);

            if ($stamp) {
                $user = $this->userRepository->load($stamp->getUserId());
                if ($user) {
                    $roles = $user->getRoles();
                    $token = new JWTUserToken($roles, $user, null, 'api');
                    $this->tokenStorage->setToken($token);
                }
            }
        }
        try {
            $envelope = $stack->next()->handle($envelope, $stack);
        } catch (\Throwable $exception) {
            $this->tokenStorage->setToken(null);
            throw $exception;
        }

        if ($envelope->last(HandledStamp::class)) {
            $this->tokenStorage->setToken(null);
        }

        return $envelope;
    }
}
