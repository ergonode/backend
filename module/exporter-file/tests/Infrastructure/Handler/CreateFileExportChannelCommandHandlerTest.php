<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Handler;

use Ergonode\ExporterFile\Infrastructure\Handler\CreateFileExportChannelCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Domain\Command\CreateFileExportChannelCommand;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class CreateFileExportChannelCommandHandlerTest extends TestCase
{
    public function testHandling(): void
    {
        $command = $this->createMock(CreateFileExportChannelCommand::class);
        $command->method('getExportType')->willReturn(FileExportChannel::EXPORT_INCREMENTAL);
        $repository = $this->createMock(ChannelRepositoryInterface::class);
        $repository->expects(self::once())->method('save');

        $handler = new CreateFileExportChannelCommandHandler($repository);
        $handler->__invoke($command);
    }
}
