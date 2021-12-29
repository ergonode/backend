<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor;

use Ergonode\ExporterFile\Infrastructure\Processor\MultimediaProcessor;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportMultimediaBuilder;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;

class MultimediaProcessorTest extends TestCase
{
    private AbstractMultimedia $multimedia;

    private FileExportChannel $channel;

    private ExportMultimediaBuilder $builder;

    protected function setUp(): void
    {
        $this->multimedia = $this->createMock(AbstractMultimedia::class);
        $this->channel = $this->createMock(FileExportChannel::class);
        $this->builder = $this->createMock(ExportMultimediaBuilder::class);
    }

    public function testProcess(): void
    {
        $data = $this->createMock(ExportData::class);
        $this->builder->expects(self::once())->method('build')->willReturn($data);

        $processor = new MultimediaProcessor($this->builder);
        $result = $processor->process($this->channel, $this->multimedia);

        self::assertSame($data, $result);
    }
}
