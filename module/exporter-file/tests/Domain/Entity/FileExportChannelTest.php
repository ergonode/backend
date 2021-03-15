<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Domain\Entity;

use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class FileExportChannelTest extends TestCase
{
    /**
     * @var ChannelId|MockObject
     */
    private $id;

    private string $name;

    private string $format;

    private string $exportType;

    protected function setUp(): void
    {
        $this->id = $this->createMock(ChannelId::class);
        $this->name = 'Name';
        $this->format = 'Format';
        $this->exportType = FileExportChannel::EXPORT_FULL;
    }

    /**
     * @throws \Exception
     */
    public function testChannelCreation(): void
    {
        $entity = new FileExportChannel($this->id, $this->name, $this->format, $this->exportType);

        self::assertEquals($this->id, $entity->getId());
        self::assertEquals($this->name, $entity->getName());
        self::assertEquals($this->format, $entity->getFormat());
        self::assertEquals($this->exportType, $entity->getExportType());
        self::assertEquals(FileExportChannel::TYPE, $entity->getType());
    }

    /**
     * @throws \Exception
     */
    public function testFormatChange(): void
    {
        $format = 'new Format';
        $entity = new FileExportChannel($this->id, $this->name, $this->format, $this->exportType);
        self::assertEquals($this->format, $entity->getFormat());
        $entity->setFormat($format);
        self::assertNotEquals($this->format, $entity->getFormat());
        self::assertEquals($format, $entity->getFormat());
    }

    /**
     * @throws \Exception
     */
    public function testExportTypeChange(): void
    {
        $exportType = FileExportChannel::EXPORT_INCREMENTAL;
        $entity = new FileExportChannel($this->id, $this->name, $this->format, $this->exportType);
        self::assertEquals($this->exportType, $entity->getExportType());
        $entity->setExportType($exportType);
        self::assertNotEquals($this->exportType, $entity->getExportType());
        self::assertEquals($exportType, $entity->getExportType());
    }
}
