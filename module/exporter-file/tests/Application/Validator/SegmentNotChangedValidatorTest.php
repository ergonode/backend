<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Application\Validator;

use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Ergonode\ExporterFile\Application\Validator\SegmentNotChanged;
use Ergonode\ExporterFile\Application\Validator\SegmentNotChangedValidator;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class SegmentNotChangedValidatorTest extends ConstraintValidatorTestCase
{
    private ExporterFileConfigurationModel $model;

    protected function setUp(): void
    {
        $this->model = $this->createMock(ExporterFileConfigurationModel::class);
        parent::setUp();
    }

    public function testWrongValueProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate(new \stdClass(), new SegmentNotChanged());
    }

    public function testWrongConstraintProvided(): void
    {
        $this->expectException(\Symfony\Component\Validator\Exception\ValidatorException::class);
        $this->validator->validate('Value', $this->createMock(Constraint::class));
    }

    public function testCorrectEmptyValidation(): void
    {
        $this->validator->validate($this->model, new SegmentNotChanged());

        $this->assertNoViolation();
    }

    public function testCorrectValueValidationSameSegment(): void
    {
        $segmentId = new SegmentId('8f693708-a188-4495-99db-79873e80c152');
        $channel = $this->createMock(FileExportChannel::class);
        $channel->expects($this->exactly(2))->method('getSegmentId')->willReturn($segmentId);
        $this->model->segmentId = $segmentId->getValue();
        $this->model->channel = $channel;

        $constraint = new SegmentNotChanged();
        $this->validator->validate($this->model, $constraint);

        $this->assertNoViolation();
    }

    public function testCorrectValueValidationNulls(): void
    {
        $channel = $this->createMock(FileExportChannel::class);
        $channel->expects(self::once())->method('getSegmentId')->willReturn(null);
        $this->model->channel = $channel;

        $constraint = new SegmentNotChanged();
        $this->validator->validate($this->model, $constraint);

        $this->assertNoViolation();
    }

    public function testInCorrectValueValidationDifferentSegment(): void
    {
        $segmentId = new SegmentId('8f693708-a188-4495-99db-79873e80c152');
        $channel = $this->createMock(FileExportChannel::class);
        $channel->expects($this->exactly(2))->method('getSegmentId')->willReturn($segmentId);
        $this->model->segmentId = 'b03be2d5-5ed0-4b80-8005-f1ec43965494';
        $this->model->channel = $channel;

        $constraint = new SegmentNotChanged();
        $this->validator->validate($this->model, $constraint);

        $assertion = $this->buildViolation($constraint->message)
            ->atPath('property.path.segmentId');
        $assertion->assertRaised();
    }

    public function testInCorrectValueValidationSegmentNull(): void
    {
        $segmentId = new SegmentId('8f693708-a188-4495-99db-79873e80c152');
        $channel = $this->createMock(FileExportChannel::class);
        $channel->expects($this->exactly(2))->method('getSegmentId')->willReturn($segmentId);
        $this->model->segmentId = null;
        $this->model->channel = $channel;

        $constraint = new SegmentNotChanged();
        $this->validator->validate($this->model, $constraint);

        $assertion = $this->buildViolation($constraint->message)
            ->atPath('property.path.segmentId');
        $assertion->assertRaised();
    }

    public function testInCorrectValueValidationNullSegment(): void
    {
        $channel = $this->createMock(FileExportChannel::class);
        $channel->expects($this->once())->method('getSegmentId')->willReturn(null);
        $this->model->segmentId = '8f693708-a188-4495-99db-79873e80c152';
        $this->model->channel = $channel;

        $constraint = new SegmentNotChanged();
        $this->validator->validate($this->model, $constraint);

        $assertion = $this->buildViolation($constraint->message)
            ->atPath('property.path.segmentId');
        $assertion->assertRaised();
    }

    protected function createValidator(): SegmentNotChangedValidator
    {
        return new SegmentNotChangedValidator();
    }
}
