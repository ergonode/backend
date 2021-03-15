<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Domain\Entity;

use Ergonode\Channel\Domain\Entity\Scheduler;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\AggregateId;
use PHPUnit\Framework\MockObject\MockObject;

class SchedulerTest extends TestCase
{
    /**
     * @var AggregateId|MockObject
     */
    private AggregateId $id;

    protected function setUp(): void
    {
        $this->id = $this->createMock(AggregateId::class);
    }

    /**
     * @throws \Exception
     */
    public function testCreateEntity(): void
    {
        $entity = new Scheduler($this->id);

        self::assertSame($this->id, $entity->getId());
        self::assertFalse($entity->isActive());
        self::assertNull($entity->getStart());
        self::assertNull($entity->getHour());
        self::assertNull($entity->getMinute());
    }

    /**
     * @throws \Exception
     */
    public function testManipulation(): void
    {
        $hour = 1;
        $minute = 1;
        $start = $this->createMock(\DateTime::class);

        $entity = new Scheduler($this->id);
        $entity->setUp(true, $start, $hour, $minute);

        self::assertTrue($entity->isActive());
        self::assertSame($start, $entity->getStart());
        self::assertSame($hour, $entity->getHour());
        self::assertSame($minute, $entity->getMinute());

        $entity->setUp(false, $start, $hour, $minute);

        self::assertFalse($entity->isActive());
        self::assertSame($start, $entity->getStart());
        self::assertSame($hour, $entity->getHour());
        self::assertSame($minute, $entity->getMinute());
    }
}
