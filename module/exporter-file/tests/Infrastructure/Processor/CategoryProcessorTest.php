<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\Processor\CategoryProcessor;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportCategoryBuilder;

class CategoryProcessorTest extends TestCase
{
    private AbstractCategory $category;

    private FileExportChannel $channel;

    private ExportCategoryBuilder $builder;

    protected function setUp(): void
    {
        $this->category = $this->createMock(AbstractCategory::class);
        $this->channel = $this->createMock(FileExportChannel::class);
        $this->builder = $this->createMock(ExportCategoryBuilder::class);
    }

    public function testProcess(): void
    {
        $data = $this->createMock(ExportData::class);
        $this->builder->expects(self::once())->method('build')->willReturn($data);

        $processor = new CategoryProcessor($this->builder);
        $result = $processor->process($this->channel, $this->category);

        self::assertSame($data, $result);
    }
}
