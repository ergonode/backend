<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Application\Validator;

use Ergonode\Multimedia\Application\Validator\MultimediaName;
use Ergonode\Multimedia\Application\Validator\MultimediaNameValidator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class MultimediaNameValidatorTest extends ConstraintValidatorTestCase
{
    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate(null, new MultimediaName());

        $this->assertNoViolation();
    }

    public function testMultimediaNameValidation(): void
    {
        $value = $this->createMock(UploadedFile::class);
        $value->method('getClientOriginalName')->willReturn('File_name.png');
        $this->validator->validate($value, new MultimediaName(['max' => 16]));

        $this->assertNoViolation();
    }

    public function testMultimediaNotNameValidation(): void
    {
        $constraint = new MultimediaName(['max' => 16]);
        $value = $this->createMock(UploadedFile::class);
        $value->method('getClientOriginalName')->willReturn('Long_file_name.png');
        $this->validator->validate($value, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ limit }}', '16');
        $assertion->assertRaised();
    }

    protected function createValidator(): MultimediaNameValidator
    {
        return new MultimediaNameValidator();
    }
}
