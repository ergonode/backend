<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor\Process;

use Ergonode\ExporterFile\Infrastructure\Processor\Process\StartFileExportProcess;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\ExporterFile\Infrastructure\Storage\FileStorage;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;

/**
 */
class StartFileExportProcessTest extends TestCase
{
    /**
     */
    public function testProcess(): void
    {
        $provider = $this->createMock(WriterProvider::class);
        $query = $this->createMock(AttributeQueryInterface::class);
        $query->expects($this->once())->method('getDictionary');
        $storage = $this->createMock(FileStorage::class);
        $storage->expects($this->once())->method('create');
        $storage->expects($this->once())->method('append');
        $storage->expects($this->once())->method('close');
        $profile = $this->createMock(FileExportProfile::class);

        $processor = new StartFileExportProcess($query, $provider, $storage);
        $processor->process($profile);
    }
}
