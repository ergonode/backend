<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Handler;

use Ergonode\ExporterFile\Infrastructure\Handler\UpdateFileExportProfileCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Domain\Command\UpdateFileExportProfileCommand;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;

/**
 */
class UpdateFileExportProfileCommandHandlerTest extends TestCase
{
    /**
     */
    public function testHandling():void
    {
        $profile = $this->createMock(FileExportProfile::class);
        $profile->expects($this->once())->method('setName');
        $profile->expects($this->once())->method('setFormat');
        $command = $this->createMock(UpdateFileExportProfileCommand::class);
        $repository = $this->createMock(ExportProfileRepositoryInterface::class);
        $repository->expects($this->once())->method('load')->willReturn($profile);
        $repository->expects($this->once())->method('save');

        $handler = new UpdateFileExportProfileCommandHandler($repository);
        $handler->__invoke($command);
    }
}
