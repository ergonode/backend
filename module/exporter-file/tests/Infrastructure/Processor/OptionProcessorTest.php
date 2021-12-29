<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\Processor\OptionProcessor;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportOptionBuilder;

class OptionProcessorTest extends TestCase
{
    private AbstractOption $option;

    private FileExportChannel $channel;

    private ExportOptionBuilder $builder;

    private AttributeRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->option = $this->createMock(AbstractOption::class);
        $this->channel = $this->createMock(FileExportChannel::class);
        $this->builder = $this->createMock(ExportOptionBuilder::class);
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
    }

    public function testProcess(): void
    {
        $data = $this->createMock(ExportData::class);
        $this->builder->expects(self::once())->method('build')->willReturn($data);
        $this->repository->expects(self::once())->method('load')
            ->willReturn($this->createMock(AbstractAttribute::class));

        $processor = new OptionProcessor($this->repository, $this->builder);
        $result = $processor->process($this->channel, $this->option);

        self::assertSame($data, $result);
    }

    public function testAttributeNodFoundProcess(): void
    {
        $this->expectException(ExportException::class);
        $this->builder->expects(self::never())->method('build');
        $this->repository->expects(self::once())->method('load')->willReturn(null);

        $processor = new OptionProcessor($this->repository, $this->builder);
        $processor->process($this->channel, $this->option);
    }
}
