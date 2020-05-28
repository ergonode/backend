<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Tests\Domain\Entity;

use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Exporter\Domain\Entity\ExportLine;

/**
 */
class ExportLineTest extends TestCase
{
    /**
     * @var ExportId|MockObject
     */
    private ExportId $exportId;

    /**
     * @var AggregateId|MockObject
     */
    private AggregateId $objectId;

    /**
     */
    protected function setUp(): void
    {
        $this->exportId = $this->createMock(ExportId::class);
        $this->objectId = $this->createMock(ChannelId::class);
    }

    /**
     */
    public function testEntityCreation(): void
    {
        $entity = new ExportLine($this->exportId, $this->objectId);
        $this->assertSame($this->exportId, $entity->getExportId());
        $this->assertSame($this->objectId, $entity->getObjectId());
        $this->assertFalse($entity->isProcessed());
        $this->assertNull($entity->getProcessedAt());
        $this->assertFalse($entity->hasError());
        $this->assertNull($entity->getError());
    }

    /**
     * @throws \Exception
     */
    public function testProcessedState(): void
    {
        $entity = new ExportLine($this->exportId, $this->objectId);
        $entity->process();
        $this->assertTrue($entity->isProcessed());
        $this->assertNotNull($entity->getProcessedAt());
        $this->assertFalse($entity->hasError());
        $this->assertNull($entity->getError());
    }

    /**
     * @throws \Exception
     */
    public function testErrorState(): void
    {
        $message = 'any message';
        $entity = new ExportLine($this->exportId, $this->objectId);
        $entity->addError($message);
        $this->assertTrue($entity->hasError());
        $this->assertsame($message, $entity->getError());
        $this->assertFalse($entity->isProcessed());
        $this->assertNull($entity->getProcessedAt());
    }
}
