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
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Ergonode\Account\Domain\Query\UserQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

/**
 */
class UserUniqueValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var MockObject|UserQueryInterface
     */
    private UserQueryInterface $query;

    /**
     */
    protected function setUp(): void
    {
        $this->query = $this->createMock(UserQueryInterface::class);
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
        $this->query->method('findIdByEmail')->willReturn(null);
        $this->validator->validate('email@example.com', new UserUnique());

        $this->assertNoViolation();
    }

    /**
     */
    public function testUserExistsValidation(): void
    {
        $userId = $this->createMock(UserId::class);
        $this->query->method('findIdByEmail')->willReturn($userId);
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
        return new UserUniqueValidator($this->query);
    }
}
