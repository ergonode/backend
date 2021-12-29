<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Application\Validator;

use Ergonode\Multimedia\Application\Validator\MultimediaType;
use Ergonode\Multimedia\Application\Validator\MultimediaTypeValidator;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Ergonode\Multimedia\Domain\Query\MultimediaTypeQueryInterface;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class MultimediaTypeValidatorTest extends ConstraintValidatorTestCase
{
    private MultimediaTypeQueryInterface $query;

    protected function setUp(): void
    {
        $this->query = $this->createMock(MultimediaTypeQueryInterface::class);
        parent::setUp();
    }

    public function testNoTypeSelectValidation(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->validator->validate('', new MultimediaType());
    }

    public function testCorrectTypeValidation(): void
    {
        $this->query->method('findMultimediaType')->willReturn('test');
        $this->validator->validate(MultimediaId::generate(), new MultimediaType('test'));

        $this->assertNoViolation();
    }

    public function testIncorrectTypeValidation(): void
    {
        $this->query->method('findMultimediaType')->willReturn('no test');
        $constrain = new MultimediaType('test');

        $this->validator->validate(MultimediaId::generate(), $constrain);

        $assertion = $this->buildViolation($constrain->message)->setParameter('{{ type }}', 'test');
        $assertion->assertRaised();
    }

    protected function createValidator(): MultimediaTypeValidator
    {
        return new MultimediaTypeValidator($this->query);
    }
}
