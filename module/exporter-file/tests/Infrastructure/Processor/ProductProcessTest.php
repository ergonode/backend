<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor;

use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\Processor\ProductProcessor;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportProductBuilder;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class ProductProcessTest extends TestCase
{
    private AbstractProduct $product;

    private FileExportChannel $channel;

    private ExportProductBuilder $builder;

    protected function setUp(): void
    {
        $this->product = $this->createMock(AbstractProduct::class);
        $this->channel = $this->createMock(FileExportChannel::class);
        $this->builder = $this->createMock(ExportProductBuilder::class);
    }

    public function testProcess(): void
    {
        $data = $this->createMock(ExportData::class);
        $this->builder->expects(self::once())->method('build')->willReturn($data);

        $processor = new ProductProcessor($this->builder);
        $result = $processor->process($this->channel, $this->product);

        self::assertSame($data, $result);
    }
}
