<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Importer\Tests\Infrastructure\Handler;

use Ergonode\Importer\Application\Service\ImportService;
use Ergonode\Importer\Domain\Command\CreateFileImportCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Handler\CreateFileImportCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateImportCommandHandlerTest extends TestCase
{
    /**
     * @var ImportService|MockObject
     */
    private $importService;

    /**
     * @var ImportRepositoryInterface|MockObject
     */
    private $importRepository;

    /**
     */
    protected function setUp()
    {
        $this->importService = $this->createMock(ImportService::class);
        $this->importService->expects($this->once())->method('import');
        $this->importRepository = $this->createMock(ImportRepositoryInterface::class);
    }

    /**
     */
    public function testHandleCommand(): void
    {
        /** @var CreateFileImportCommand|MockObject $command */
        $command = $this->createMock(CreateFileImportCommand::class);
        $handler = new CreateFileImportCommandHandler($this->importService, $this->importRepository);
        $handler->__invoke($command);
    }
}
