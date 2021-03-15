<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Application\Validator;

use Ergonode\Account\Application\Validator\LanguageActive;
use Ergonode\Account\Application\Validator\LanguageActiveValidator;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class LanguageActiveValidatorTest extends ConstraintValidatorTestCase
{
    private LanguageQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(LanguageQueryInterface::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate(new \stdClass(), new LanguageActive());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        /** @var Constraint $constrain */
        $constrain = $this->createMock(Constraint::class);
        $this->validator->validate('Value', $constrain);
    }

    public function testLanguageCodeValidation(): void
    {
        $value = ['code1' => 'code1'];
        $this->query->method('getDictionaryActive')->willReturn(['code1' => 'code1']);
        $this->validator->validate($value, new LanguageActive());

        $this->assertNoViolation();
    }

    public function testLanguageCodeInvalidValidation(): void
    {
        $value = ['code1' => 'code1'];
        $this->query->method('getDictionaryActive')->willReturn(['code2' => 'code2']);
        $constraint = new LanguageActive();
        $this->validator->validate($value, $constraint);
        $assertion = $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', 'code1');
        $assertion->assertRaised();
    }

    protected function createValidator(): LanguageActiveValidator
    {
        return new LanguageActiveValidator($this->query);
    }
}
