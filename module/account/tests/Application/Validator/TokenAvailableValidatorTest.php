<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Application\Validator;

use Ergonode\Account\Application\Validator\TokenAvailable;
use Ergonode\Account\Application\Validator\TokenAvailableValidator;
use Ergonode\Account\Domain\Validator\TokenValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class TokenAvailableValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var TokenValidator|MockObject
     */
    private TokenValidator $tokenValidator;

    public function setUp(): void
    {
        $this->tokenValidator =  $this->createMock(TokenValidator::class);

        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new TokenAvailable());
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
        $this->validator->validate('', new TokenAvailable());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidation(): void
    {
        $this->tokenValidator->method('validate')
            ->willReturn(true);

        $this->validator->validate('test', new TokenAvailable());

        $this->assertNoViolation();
    }

    public function testInCorrectTimeValueValidation(): void
    {
        $this->tokenValidator->method('validate')
            ->willReturn(false);

        $constraint = new TokenAvailable();
        $value = 'test';
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->validMessage);
        $assertion->assertRaised();
    }

    protected function createValidator(): TokenAvailableValidator
    {
        return new TokenAvailableValidator($this->tokenValidator);
    }
}
