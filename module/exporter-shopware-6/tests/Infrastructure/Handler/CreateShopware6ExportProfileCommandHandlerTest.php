<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Handler;

use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\CreateShopware6ExportProfileCommand;
use Ergonode\ExporterShopware6\Infrastructure\Handler\CreateShopware6ExportProfileCommandHandler;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateShopware6ExportProfileCommandHandlerTest extends TestCase
{
    /**
     */
    public function testHandling():void
    {
        $command = $this->createMock(CreateShopware6ExportProfileCommand::class);
        $repository = $this->createMock(ExportProfileRepositoryInterface::class);
        $repository->expects($this->once())->method('save');

        $handler = new CreateShopware6ExportProfileCommandHandler($repository);
        $handler->__invoke($command);
    }
}
