<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Application\Validator;

use Ergonode\Multimedia\Application\Validator\MultimediaExtensionValidator;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaExtensionProvider;
use Ergonode\Multimedia\Application\Validator\MultimediaExtension;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MultimediaExtensionValidatorTest extends ConstraintValidatorTestCase
{
    private MultimediaExtensionProvider $provider;

    protected function setUp(): void
    {
        $this->provider = $this->createMock(MultimediaExtensionProvider::class);
        parent::setUp();
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate(null, new MultimediaExtension());

        $this->assertNoViolation();
    }

    public function testMultimediaExtensionValidation(): void
    {
        $value = $this->createMock(UploadedFile::class);
        $value->method('getClientOriginalExtension')->willReturn('png');
        $this->provider->method('dictionary')->willReturn(['png', 'jpg']);
        $this->validator->validate($value, new MultimediaExtension());

        $this->assertNoViolation();
    }

    public function testMultimediaNotExistsValidation(): void
    {
        $this->provider->method('dictionary')->willReturn([]);
        $constrain = new MultimediaExtension();
        $value = $this->createMock(UploadedFile::class);
        $value->method('getClientOriginalExtension')->willReturn('png');

        $this->validator->validate($value, $constrain);

        $assertion = $this->buildViolation($constrain->message)->setParameter('{{ value }}', 'png');
        $assertion->assertRaised();
    }

    protected function createValidator(): MultimediaExtensionValidator
    {
        return new MultimediaExtensionValidator($this->provider);
    }
}
