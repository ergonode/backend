<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Handler;

use Ergonode\ExporterFile\Infrastructure\Handler\UpdateFileExportChannelCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Domain\Command\UpdateFileExportChannelCommand;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;

class UpdateFileExportChannelCommandHandlerTest extends TestCase
{
    public function testHandling(): void
    {
        $channel = $this->createMock(FileExportChannel::class);
        $channel->expects(self::once())->method('setName');
        $channel->expects(self::once())->method('setFormat');
        $command = $this->createMock(UpdateFileExportChannelCommand::class);
        $command->method('getExportType')->willReturn(FileExportChannel::EXPORT_INCREMENTAL);
        $repository = $this->createMock(ChannelRepositoryInterface::class);
        $repository->expects(self::once())->method('load')->willReturn($channel);
        $repository->expects(self::once())->method('save');

        $handler = new UpdateFileExportChannelCommandHandler($repository);
        $handler->__invoke($command);
    }
}
