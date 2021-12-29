<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Infrastructure\Processor\TemplateElementProcessor;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportTemplateElementBuilder;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;

class TemplateElementProcessorTest extends TestCase
{
    private ExportTemplateElementBuilder $builder;

    private Template $template;

    private FileExportChannel $channel;

    protected function setUp(): void
    {
        $this->builder = $this->createMock(ExportTemplateElementBuilder::class);
        $this->template = $this->createMock(Template::class);
        $this->channel = $this->createMock(FileExportChannel::class);
    }

    public function testProcessor(): void
    {
        $data = $this->createMock(ExportData::class);
        $this->builder->expects(self::once())->method('build')->willReturn($data);

        $processor = new TemplateElementProcessor($this->builder);
        $result = $processor->process($this->channel, $this->template);

        self::assertSame($data, $result);
    }
}
