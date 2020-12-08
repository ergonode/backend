<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Application\Validator\Constraints;

use Ergonode\Account\Application\Validator\Constraints\AvailableTokenConstraint;
use Ergonode\Account\Application\Validator\Constraints\AvailableTokenConstraintValidator;
use Ergonode\Account\Domain\Entity\UserResetPasswordToken;
use Ergonode\Account\Domain\Repository\UserResetPasswordTokenRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class AvailableTokenConstraintValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var UserResetPasswordTokenRepositoryInterface|MockObject
     */
    private UserResetPasswordTokenRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->repository = $this->createMock(UserResetPasswordTokenRepositoryInterface::class);

        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new AvailableTokenConstraint());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\UnexpectedTypeException::class);
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constrain);
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new AvailableTokenConstraint());

        $this->assertNoViolation();
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

        $this->validator->validate('test', new AvailableTokenConstraint());

        $this->assertNoViolation();
    }

    public function testInCorrectTimeValueValidation(): void
    {
        $expiresAt = new \DateTime();

        $userToken = $this->createMock(UserResetPasswordToken::class);
        $userToken->method('getExpiresAt')
            ->willReturn($expiresAt);

        $this->repository->method('load')
            ->willReturn($userToken);

        $constraint = new AvailableTokenConstraint();
        $value = 'test';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->validMessage);
        $assertion->assertRaised();
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

        $constraint = new AvailableTokenConstraint();
        $value = 'test';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->validMessage);
        $assertion->assertRaised();
    }

    public function testInCorrectNoTokenValueValidation(): void
    {
        $this->repository->method('load')
            ->willReturn(null);

        $constraint = new AvailableTokenConstraint();
        $value = 'test';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->validMessage);
        $assertion->assertRaised();
    }

    public function testInCorrectTokenValueValidation(): void
    {
        $constraint = new AvailableTokenConstraint();
        $value = str_repeat('a', 256);
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->validMessage);
        $assertion->assertRaised();
    }

    protected function createValidator(): AvailableTokenConstraintValidator
    {
        return new AvailableTokenConstraintValidator($this->repository);
    }
}
