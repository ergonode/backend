<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Application\Validator;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Ergonode\Multimedia\Application\Validator\MultimediaUploadName;
use Ergonode\Multimedia\Application\Validator\MultimediaUploadNameValidator;

class MultimediaUploadNameValidatorTest extends ConstraintValidatorTestCase
{
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate(null, new MultimediaUploadName());

        $this->assertNoViolation();
    }

    public function testMultimediaNameValidation(): void
    {
        $value = $this->createMock(UploadedFile::class);
        $value->method('getClientOriginalName')->willReturn('File_name.png');
        $this->validator->validate($value, new MultimediaUploadName());

        $this->assertNoViolation();
    }

    public function testMultimediaLongNameValidation(): void
    {
        $constraint = new MultimediaUploadName();
        $value = $this->createMock(UploadedFile::class);
        $value->method('getClientOriginalName')->willReturn(str_repeat('a', 128).'.png');
        $this->validator->validate($value, $constraint);

        $assertion = $this
            ->buildViolation($constraint->messageMax)
            ->setParameter('{{ limit }}', '128');
        $assertion->assertRaised();
    }

    public function testMultimediaInvalidCharacterValidation(): void
    {
        $constraint = new MultimediaUploadName();
        $value = $this->createMock(UploadedFile::class);
        $value->method('getClientOriginalName')->willReturn('invalid/character.png');
        $this->validator->validate($value, $constraint);

        $assertion = $this
            ->buildViolation($constraint->message);
        $assertion->assertRaised();
    }

    protected function createValidator(): MultimediaUploadNameValidator
    {
        return new MultimediaUploadNameValidator();
    }
}
