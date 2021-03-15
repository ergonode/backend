<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Domain\Entity;

use Ergonode\Channel\Domain\Entity\Export;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Channel\Domain\ValueObject\ExportStatus;
use PHPUnit\Framework\MockObject\MockObject;

class ExportTest extends TestCase
{
    /**
     * @var ExportId|MockObject
     */
    private ExportId $id;

    /**
     * @var ChannelId|MockObject
     */
    private ChannelId $channelId;

    protected function setUp(): void
    {
        $this->id = $this->createMock(ExportId::class);
        $this->channelId = $this->createMock(ChannelId::class);
    }

    public function testEntityCreation(): void
    {
        $entity = new Export($this->id, $this->channelId);
        self::assertSame($this->id, $entity->getId());
        self::assertEquals($this->channelId, $entity->getChannelId());
        self::assertSame(ExportStatus::CREATED, $entity->getStatus()->getValue());
        self::assertNull($entity->getStartedAt());
        self::assertNull($entity->getEndedAt());
    }

    public function testExportStatus(): void
    {
        $entity = new Export($this->id, $this->channelId);
        $entity->start();
        self::assertSame(ExportStatus::PRECESSED, $entity->getStatus()->getValue());
        self::assertNotNull($entity->getStartedAt());
        $entity->end();
        self::assertSame(ExportStatus::ENDED, $entity->getStatus()->getValue());
        self::assertNotNull($entity->getEndedAt());
        $entity->stop();
        self::assertSame(ExportStatus::STOPPED, $entity->getStatus()->getValue());
    }

    public function testInvalidEndStatusChange(): void
    {
        $this->expectException(\LogicException::class);
        $entity = new Export($this->id, $this->channelId);
        $entity->end();
    }

    public function testInvalidStartStatusChange(): void
    {
        $this->expectException(\LogicException::class);
        $entity = new Export($this->id, $this->channelId);
        $entity->start();
        $entity->start();
    }

    public function testInvalidStopStatusChange(): void
    {
        $this->expectException(\LogicException::class);
        $entity = new Export($this->id, $this->channelId);
        $entity->stop();
        $entity->stop();
    }
}
