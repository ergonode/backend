<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Authentication\Application\Security\Authenticator;

use Ergonode\Account\Domain\Exception\InvalidEmailException;
use Ergonode\Authentication\Application\Security\Authenticator\FormAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationFailureHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 */
class FormAuthenticatorTest extends TestCase
{
    /**
     * @var MockObject|UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var MockObject|AuthenticationSuccessHandler
     */
    private $successHandler;

    /**
     * @var MockObject|AuthenticationFailureHandler
     */
    private $failureHandler;

    /**
     */
    public function setUp(): void
    {
        $this->passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->successHandler = $this->createMock(AuthenticationSuccessHandler::class);
        $this->failureHandler = $this->createMock(AuthenticationFailureHandler::class);
    }

    /**
     */
    public function testSupports(): void
    {
        $request = $this->createMock(Request::class);
        $authenticator = new FormAuthenticator($this->passwordEncoder, $this->successHandler, $this->failureHandler);
        $this->assertTrue($authenticator->supports($request));
    }

    /**
     */
    public function testGetCredentials(): void
    {
        $request = $this->createMock(Request::class);
        $parameterBag = $this->createMock(ParameterBag::class);
        $request->request = $parameterBag;
        $parameterBag->expects($this->at(0))->method('get')->willReturn('username');
        $parameterBag->expects($this->at(1))->method('get')->willReturn('pass');
        $authenticator = new FormAuthenticator($this->passwordEncoder, $this->successHandler, $this->failureHandler);
        $this->assertSame([
            'email' => 'username',
            'password' => 'pass',
        ], $authenticator->getCredentials($request));
    }

    /**
     */
    public function testGetUser(): void
    {
        $credential = [
            'email' => 'username',
            'password' => 'pass',
        ];
        $userProvider = $this->createMock(UserProviderInterface::class);
        $user = $this->createMock(UserInterface::class);
        $userProvider->expects($this->once())->method('loadUserByUsername')->willReturn($user);
        $authenticator = new FormAuthenticator($this->passwordEncoder, $this->successHandler, $this->failureHandler);
        $this->assertSame($user, $authenticator->getUser($credential, $userProvider));
    }

    /**
     */
    public function testGetUserException(): void
    {
        $this->expectException(\Symfony\Component\Security\Core\Exception\AuthenticationException::class);
        $credentials = [
            'email' => 'username',
            'password' => 'pass',
        ];
        $userProvider = $this->createMock(UserProviderInterface::class);
        $exception = $this->createMock(InvalidEmailException::class);
        $userProvider->expects($this->once())->method('loadUserByUsername')->willThrowException($exception);
        $authenticator = new FormAuthenticator($this->passwordEncoder, $this->successHandler, $this->failureHandler);
        $authenticator->getUser($credentials, $userProvider);
    }

    /**
     */
    public function testCheckCredentials(): void
    {
        $credentials = [
            'email' => 'username',
            'password' => 'pass',
        ];
        $user = $this->createMock(UserInterface::class);
        $this->passwordEncoder->expects($this->once())->method('isPasswordValid')->willReturn(true);
        $authenticator = new FormAuthenticator($this->passwordEncoder, $this->successHandler, $this->failureHandler);
        $authenticator->checkCredentials($credentials, $user);
    }

    /**
     *
     */
    public function testCheckCredentialsException(): void
    {
        $this->expectException(\Symfony\Component\Security\Core\Exception\AuthenticationException::class);
        $this->expectExceptionMessage("Invalid password");
        $credentials = [
            'email' => 'username',
            'password' => 'pass',
        ];
        $user = $this->createMock(UserInterface::class);
        $this->passwordEncoder->expects($this->once())->method('isPasswordValid')->willReturn(false);
        $authenticator = new FormAuthenticator($this->passwordEncoder, $this->successHandler, $this->failureHandler);
        $authenticator->checkCredentials($credentials, $user);
    }
}
