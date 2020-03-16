<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

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

/**
 */
class FormAuthenticator extends AbstractGuardAuthenticator implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * @var AuthenticationSuccessHandler
     */
    private AuthenticationSuccessHandler $successHandler;

    /**
     * @var AuthenticationFailureHandler
     */
    private AuthenticationFailureHandler $failureHandler;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param AuthenticationSuccessHandler $successHandler
     * @param AuthenticationFailureHandler $failureHandler
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        AuthenticationSuccessHandler $successHandler,
        AuthenticationFailureHandler $failureHandler
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->successHandler = $successHandler;
        $this->failureHandler = $failureHandler;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return true;
    }

    /**
     * @param Request $request
     *
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
     * @param mixed                 $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return UserInterface|null
     */
    public function getUser(
        $credentials,
        UserProviderInterface $userProvider
    ): ?UserInterface {
        try {
            return $userProvider->loadUserByUsername($credentials['email']);
        } catch (InvalidEmailException $exception) {
            throw new AuthenticationException('Username not found');
        }
    }

    /**
     * @param mixed         $credentials
     * @param UserInterface $user
     *
     * @return bool
     */
    public function checkCredentials(
        $credentials,
        UserInterface $user
    ): bool {
        $isValid = $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
        if (!$isValid) {
            throw new AuthenticationException('Invalid password');
        }

        return true;
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $providerKey
     *
     * @return Response
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

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response
     */
    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): Response {
        return $this->failureHandler->onAuthenticationFailure($request, $exception);
    }

    /**
     * @param Request                      $request
     * @param AuthenticationException|null $authException
     *
     * @return Response
     */
    public function start(
        Request $request,
        AuthenticationException $authException = null
    ): Response {
        return new Response(null, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return bool
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}
