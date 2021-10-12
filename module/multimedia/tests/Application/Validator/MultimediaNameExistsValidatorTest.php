<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Application\Validator;

use Ergonode\Multimedia\Application\Model\MultimediaModel;
use Ergonode\Multimedia\Application\Validator\MultimediaNameExists;
use Ergonode\Multimedia\Application\Validator\MultimediaNameExistsValidator;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class MultimediaNameExistsValidatorTest extends ConstraintValidatorTestCase
{
    private MultimediaQueryInterface $multimediaQuery;
    private MultimediaRepositoryInterface $multimediaRepository;

    protected function setUp(): void
    {
        $this->multimediaQuery = $this->createMock(MultimediaQueryInterface::class);
        $this->multimediaRepository = $this->createMock(MultimediaRepositoryInterface::class);
        parent::setUp();
    }

    public function testCorrectEmptyValidation(): void
    {
        $model = $this->createMock(MultimediaModel::class);
        $this->validator->validate($model, new MultimediaNameExists());

        $this->assertNoViolation();
    }

    public function testMultimediaNameNotChangedValidation(): void
    {
        $multimedia = $this->createMock(Multimedia::class);
        $multimedia->expects($this->once())->method('getName')->willReturn('test');
        $this->multimediaRepository->expects($this->once())->method('load')->willReturn($multimedia);
        $model = $this->createMock(MultimediaModel::class);
        $model->multimediaId = $this->createMock(MultimediaId::class);
        $model->name = 'test';
        $this->validator->validate($model, new MultimediaNameExists());

        $this->assertNoViolation();
    }

    public function testMultimediaNameNotExistingValidation(): void
    {
        $multimedia = $this->createMock(Multimedia::class);
        $multimedia->expects($this->once())->method('getName')->willReturn('test');
        $this->multimediaRepository->expects($this->once())->method('load')->willReturn($multimedia);
        $this->multimediaQuery->expects($this->once())->method('findIdByFilename')->willReturn(null);
        $model = $this->createMock(MultimediaModel::class);
        $model->multimediaId = $this->createMock(MultimediaId::class);
        $model->name = 'test_1';
        $this->multimediaQuery->expects($this->once())->method('findIdByFilename')->willReturn(null);

        $this->validator->validate($model, new MultimediaNameExists());

        $this->assertNoViolation();
    }

    public function testMultimediaExistsValidation(): void
    {
        $this->multimediaQuery->method('findIdByFilename')->willReturn($this->createMock(MultimediaId::class));
        $multimedia = $this->createMock(Multimedia::class);
        $multimedia->expects($this->once())->method('getName')->willReturn('test');
        $this->multimediaRepository->expects($this->once())->method('load')->willReturn($multimedia);
        $this->multimediaQuery->expects($this->once())->method('findIdByFilename')->willReturn(null);
        $model = $this->createMock(MultimediaModel::class);
        $model->multimediaId = $this->createMock(MultimediaId::class);
        $model->name = 'test_1';
        $constraint = new MultimediaNameExists();
        $this->validator->validate($model, $constraint);

        $assertion = $this->buildViolation($constraint->message)->setParameter('{{ value }}', $model->name);
        $assertion->assertRaised();
    }

    protected function createValidator(): MultimediaNameExistsValidator
    {
        return new MultimediaNameExistsValidator($this->multimediaQuery, $this->multimediaRepository);
    }
}
