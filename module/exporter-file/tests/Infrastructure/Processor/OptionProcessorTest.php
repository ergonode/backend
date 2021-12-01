<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor;

use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportOptionBuilder;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\Processor\OptionProcessor;
use PHPUnit\Framework\TestCase;

class OptionProcessorTest extends TestCase
{
    private AbstractOption $option;

    private FileExportChannel $channel;

    private ExportOptionBuilder $builder;

    protected function setUp(): void
    {
        $this->option = $this->createMock(AbstractOption::class);
        $this->channel = $this->createMock(FileExportChannel::class);
        $this->builder = $this->createMock(ExportOptionBuilder::class);
    }

    public function testProcess(): void
    {
        $data = $this->createMock(ExportData::class);
        $this->builder->expects(self::once())->method('build')->willReturn($data);

        $processor = new OptionProcessor($this->builder);
        $result = $processor->process($this->channel, $this->option);

        self::assertSame($data, $result);
    }
}
