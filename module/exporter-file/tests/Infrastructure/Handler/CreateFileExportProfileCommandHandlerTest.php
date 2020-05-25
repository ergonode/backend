<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Handler;

use Ergonode\ExporterFile\Infrastructure\Handler\CreateFileExportProfileCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Domain\Command\CreateFileExportProfileCommand;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;

/**
 */
class CreateFileExportProfileCommandHandlerTest extends TestCase
{
    /**
     */
    public function testHandling():void
    {
        $command = $this->createMock(CreateFileExportProfileCommand::class);
        $repository = $this->createMock(ExportProfileRepositoryInterface::class);
        $repository->expects($this->once())->method('save');

        $handler = new CreateFileExportProfileCommandHandler($repository);
        $handler->__invoke($command);
    }
}
