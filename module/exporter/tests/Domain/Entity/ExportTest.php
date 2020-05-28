<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Tests\Domain\Entity;

use Ergonode\Exporter\Domain\Entity\Export;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Ergonode\Exporter\Domain\ValueObject\ExportStatus;
use PHPUnit\Framework\MockObject\MockObject;

/**
 */
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

    /**
     * @var ExportProfileId|MockObject
     */
    private ExportProfileId $profileId;

    /**
     * @var int
     */
    private int $items;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(ExportId::class);
        $this->channelId = $this->createMock(ChannelId::class);
        $this->profileId = $this->createMock(ExportProfileId::class);
        $this->items = 0;
    }

    /**
     */
    public function testEntityCreation(): void
    {
        $entity = new Export($this->id, $this->channelId, $this->profileId, $this->items);
        $this->assertSame($this->id, $entity->getId());
        $this->assertEquals($this->channelId, $entity->getChannelId());
        $this->assertEquals($this->profileId, $entity->getExportProfileId());
        $this->assertSame($this->items, $entity->getItems());
        $this->assertSame(ExportStatus::CREATED, $entity->getStatus()->getValue());
        $this->assertNull($entity->getStartedAt());
        $this->assertNull($entity->getEndedAt());
    }

    /**
     */
    public function testExportStatus(): void
    {
        $entity = new Export($this->id, $this->channelId, $this->profileId, $this->items);
        $entity->start();
        $this->assertSame(ExportStatus::PRECESSED, $entity->getStatus()->getValue());
        $this->assertNotNull($entity->getStartedAt());
        $entity->end();
        $this->assertSame(ExportStatus::ENDED, $entity->getStatus()->getValue());
        $this->assertNotNull($entity->getEndedAt());
        $entity->stop();
        $this->assertSame(ExportStatus::STOPPED, $entity->getStatus()->getValue());
    }

    /**
     */
    public function testInvalidEndStatusChange(): void
    {
        $this->expectException(\LogicException::class);
        $entity = new Export($this->id, $this->channelId, $this->profileId, $this->items);
        $entity->end();
    }

    /**
     */
    public function testInvalidStartStatusChange(): void
    {
        $this->expectException(\LogicException::class);
        $entity = new Export($this->id, $this->channelId, $this->profileId, $this->items);
        $entity->start();
        $entity->start();
    }

    /**
     */
    public function testInvalidStopStatusChange(): void
    {
        $this->expectException(\LogicException::class);
        $entity = new Export($this->id, $this->channelId, $this->profileId, $this->items);
        $entity->stop();
        $entity->stop();
    }
}
