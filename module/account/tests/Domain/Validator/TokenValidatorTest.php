<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Validator;

use Ergonode\Account\Domain\Entity\UserResetPasswordToken;
use Ergonode\Account\Domain\Repository\UserResetPasswordTokenRepositoryInterface;
use Ergonode\Account\Domain\Validator\TokenValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TokenValidatorTest extends TestCase
{
    /**
     * @var UserResetPasswordTokenRepositoryInterface|MockObject
     */
    private UserResetPasswordTokenRepositoryInterface $repository;

    private TokenValidator $tokenValidator;

    public function setUp(): void
    {
        $this->repository = $this->createMock(UserResetPasswordTokenRepositoryInterface::class);

        $this->tokenValidator = new TokenValidator($this->repository);

        parent::setUp();
    }

    public function testCorrectValueValidation(): void
    {
        $expiresAt = new \DateTime();
        $expiresAt->add(new \DateInterval('PT1H'));

        $userToken = $this->createMock(UserResetPasswordToken::class);
        $userToken->method('getExpiresAt')
            ->willReturn($expiresAt);

        $this->repository->method('load')
            ->willReturn($userToken);

        self::assertTrue($this->tokenValidator->validate('test'));
    }

    public function testInCorrectTimeValueValidation(): void
    {
        $expiresAt = new \DateTime();

        $userToken = $this->createMock(UserResetPasswordToken::class);
        $userToken->method('getExpiresAt')
            ->willReturn($expiresAt);

        $this->repository->method('load')
            ->willReturn($userToken);

        self::assertFalse($this->tokenValidator->validate('test'));
    }

    public function testInCorrectConsumedValueValidation(): void
    {
        $expiresAt = new \DateTime();
        $expiresAt->add(new \DateInterval('PT1H'));
        $now = new \DateTime();

        $userToken = $this->createMock(UserResetPasswordToken::class);
        $userToken->method('getExpiresAt')
            ->willReturn($expiresAt);
        $userToken->method('getConsumed')
            ->willReturn($now);

        $this->repository->method('load')
            ->willReturn($userToken);

        self::assertFalse($this->tokenValidator->validate('test'));
    }

    public function testInCorrectNoTokenValueValidation(): void
    {
        $this->repository->method('load')
            ->willReturn(null);

        self::assertFalse($this->tokenValidator->validate('test'));
    }

    public function testInCorrectTokenValueValidation(): void
    {
        $value = str_repeat('a', 256);
        self::assertFalse($this->tokenValidator->validate($value));
    }

    public function testInCorrectEmptyValidation(): void
    {
        self::assertFalse($this->tokenValidator->validate(''));
    }
}
