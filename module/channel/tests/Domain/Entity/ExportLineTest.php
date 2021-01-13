<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Domain\Entity;

use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Channel\Domain\Entity\ExportLine;

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

    protected function setUp(): void
    {
        $this->exportId = $this->createMock(ExportId::class);
        $this->objectId = $this->createMock(ChannelId::class);
    }

    public function testEntityCreation(): void
    {
        $entity = new ExportLine($this->exportId, $this->objectId);
        self::assertSame($this->exportId, $entity->getExportId());
        self::assertSame($this->objectId, $entity->getObjectId());
        self::assertFalse($entity->isProcessed());
        self::assertNull($entity->getProcessedAt());
        self::assertFalse($entity->hasError());
        self::assertNull($entity->getError());
    }

    /**
     * @throws \Exception
     */
    public function testProcessedState(): void
    {
        $entity = new ExportLine($this->exportId, $this->objectId);
        $entity->process();
        self::assertTrue($entity->isProcessed());
        self::assertNotNull($entity->getProcessedAt());
        self::assertFalse($entity->hasError());
        self::assertNull($entity->getError());
    }

    /**
     * @throws \Exception
     */
    public function testErrorState(): void
    {
        $message = 'any message';
        $entity = new ExportLine($this->exportId, $this->objectId);
        $entity->addError($message);
        self::assertTrue($entity->hasError());
        self::assertsame($message, $entity->getError());
        self::assertFalse($entity->isProcessed());
        self::assertNull($entity->getProcessedAt());
    }
}
