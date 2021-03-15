<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Security\Authenticator;

use Ergonode\Account\Domain\Exception\InvalidEmailException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationFailureHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class FormAuthenticator extends AbstractGuardAuthenticator implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private UserPasswordEncoderInterface $passwordEncoder;

    private AuthenticationSuccessHandler $successHandler;

    private AuthenticationFailureHandler $failureHandler;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        AuthenticationSuccessHandler $successHandler,
        AuthenticationFailureHandler $failureHandler
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->successHandler = $successHandler;
        $this->failureHandler = $failureHandler;
    }

    public function supports(Request $request): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function getCredentials(Request $request): array
    {
        return [
            'email' => $request->request->get('username'),
            'password' => $request->request->get('password'),
        ];
    }

    /**
     * @param mixed $credentials
     */
    public function getUser(
        $credentials,
        UserProviderInterface $userProvider
    ): ?UserInterface {
        try {
            return $userProvider->loadUserByUsername($credentials['email']);
        } catch (InvalidEmailException $exception) {
            throw new AuthenticationException('Invalid credentials');
        }
    }

    /**
     * @param mixed $credentials
     */
    public function checkCredentials(
        $credentials,
        UserInterface $user
    ): bool {
        $isValid = $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
        if (!$isValid) {
            throw new AuthenticationException('Invalid credentials');
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    ): Response {
        return $this->successHandler->onAuthenticationSuccess($request, $token);
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): Response {
        return $this->failureHandler->onAuthenticationFailure($request, $exception);
    }

    public function start(
        Request $request,
        AuthenticationException $authException = null
    ): Response {
        return new Response(null, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
