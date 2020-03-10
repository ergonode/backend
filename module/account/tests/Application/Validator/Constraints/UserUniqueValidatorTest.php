<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Account\Application\Validator\Constraints;

use Ergonode\Account\Application\Validator\Constraints\UserUnique;
use Ergonode\Account\Application\Validator\Constraints\UserUniqueValidator;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 */
class UserUniqueValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var MockObject|UserRepositoryInterface
     */
    private $repository;

    /**
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepositoryInterface::class);
        parent::setUp();
    }

    /**
     */
    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new UserUnique());
    }

    /**
     */
    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        /** @var Constraint $constraint */
        $constraint = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constraint);
    }

    /**
     */
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate('', new UserUnique());

        $this->assertNoViolation();
    }

    /**
     */
    public function testStatusNotExistsValidation(): void
    {
        $this->repository->method('load')->willReturn(null);
        $this->validator->validate('email@example.com', new UserUnique());

        $this->assertNoViolation();
    }

    /**
     */
    public function testUserExistsValidation(): void
    {
        $this->repository->method('load')->willReturn($this->createMock(User::class));
        $constraint = new UserUnique();
        $value = 'email@example.com';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $value);
        $assertion->assertRaised();
    }

    /**
     * @return UserUniqueValidator
     */
    protected function createValidator(): UserUniqueValidator
    {
        return new UserUniqueValidator($this->repository);
    }
}
