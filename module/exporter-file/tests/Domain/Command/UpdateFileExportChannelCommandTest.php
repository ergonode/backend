<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Domain\Command;

use Ergonode\ExporterFile\Domain\Command\UpdateFileExportChannelCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class UpdateFileExportChannelCommandTest extends TestCase
{
    public function testCommand(): void
    {
        $id = $this->createMock(ChannelId::class);
        $name = 'Name';
        $format = 'Format';
        $exportType = FileExportChannel::EXPORT_INCREMENTAL;
        $command = new UpdateFileExportChannelCommand($id, $name, $format, $exportType);
        self::assertEquals($id, $command->getId());
        self::assertEquals($name, $command->getName());
        self::assertEquals($exportType, $command->getExportType());
        self::assertEquals($format, $command->getFormat());
    }
}
