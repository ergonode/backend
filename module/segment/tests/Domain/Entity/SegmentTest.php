<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Tests\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class SegmentTest extends TestCase
{
    /**
     * @var SegmentId|MockObject
     */
    private $id;

    /**
     * @var SegmentCode|MockObject
     */
    private $code;

    /**
     * @var TranslatableString|MockObject
     */
    private $name;

    /**
     * @var TranslatableString|MockObject
     */
    private $description;

    /**
     * @var ConditionSetId|MockObject
     */
    private $conditionSetId;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(SegmentId::class);
        $this->code = $this->createMock(SegmentCode::class);
        $this->name = $this->createMock(TranslatableString::class);
        $this->description = $this->createMock(TranslatableString::class);
        $this->conditionSetId = $this->createMock(ConditionSetId::class);
    }

    /**
     * @throws \Exception
     */
    public function testSegmentCreation():void
    {
        $segment = new Segment($this->id, $this->code, $this->name, $this->description, $this->conditionSetId);

        self::assertEquals($this->id, $segment->getId());
        self::assertEquals($this->code, $segment->getCode());
        self::assertEquals($this->name, $segment->getName());
        self::assertEquals($this->description, $segment->getDescription());
        self::assertEquals($this->conditionSetId, $segment->getConditionSetId());
        self::assertEquals(new SegmentStatus(SegmentStatus::NEW), $segment->getStatus());
    }

    /**
     * @throws \Exception
     */
    public function testSegmentManipulation():void
    {
        /** @var TranslatableString|MockObject $name */
        $name = $this->createMock(TranslatableString::class);
        $name->method('isEqual')->willReturn(false);

        /** @var TranslatableString|MockObject $description */
        $description = $this->createMock(TranslatableString::class);
        $description->method('isEqual')->willReturn(false);

        /** @var SegmentStatus|MockObject $status */
        $status = $this->createMock(SegmentStatus::class);
        $status->method('isEqual')->willReturn(false);

        /** @var ConditionSetId|MockObject $conditionSet */
        $conditionSetId = $this->createMock(ConditionSetId::class);
        $conditionSetId->method('isEqual')->willReturn(false);

        $segment = new Segment($this->id, $this->code, $this->name, $this->description, $this->conditionSetId);
        $segment->changeStatus($status);
        $segment->changeName($name);
        $segment->changeDescription($description);
        $segment->changeConditionSet($conditionSetId);

        self::assertSame($name, $segment->getName());
        self::assertSame($description, $segment->getDescription());
        self::assertSame($status, $segment->getStatus());
        self::assertSame($conditionSetId, $segment->getConditionSetId());
    }
}
