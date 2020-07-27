<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor\Process;

use Ergonode\ExporterFile\Infrastructure\Processor\Process\EndFileExportProcess;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\ExporterFile\Infrastructure\Storage\FileStorage;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class EndFileExportProcessTest extends TestCase
{
    /**
     */
    public function testProcess(): void
    {
        $id = $this->createMock(ExportId::class);
        $provider = $this->createMock(WriterProvider::class);
        $query = $this->createMock(AttributeQueryInterface::class);
        $query->expects(self::once())->method('getDictionary');
        $storage = $this->createMock(FileStorage::class);
        $storage->expects(self::once())->method('open');
        $storage->expects(self::once())->method('append');
        $storage->expects(self::once())->method('close');
        $channel = $this->createMock(FileExportChannel::class);

        $processor = new EndFileExportProcess($query, $provider, $storage);
        $processor->process($id, $channel);
    }
}
