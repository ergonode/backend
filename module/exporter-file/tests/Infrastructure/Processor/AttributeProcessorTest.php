<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\Processor\AttributeProcessor;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportAttributeBuilder;

class AttributeProcessorTest extends TestCase
{
    private AbstractAttribute $attribute;

    private FileExportChannel $channel;

    private ExportAttributeBuilder $builder;

    protected function setUp(): void
    {
        $this->attribute = $this->createMock(AbstractAttribute::class);
        $this->channel = $this->createMock(FileExportChannel::class);
        $this->builder = $this->createMock(ExportAttributeBuilder::class);
    }

    public function testProcess(): void
    {
        $data = $this->createMock(ExportData::class);
        $this->builder->expects(self::once())->method('build')->willReturn($data);

        $processor = new AttributeProcessor($this->builder);
        $result = $processor->process($this->channel, $this->attribute);

        self::assertSame($data, $result);
    }
}
