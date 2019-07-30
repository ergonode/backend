<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Importer\Tests\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\StartProcessImportCommand;
use Ergonode\Importer\Domain\Entity\AbstractImport;
use Ergonode\Importer\Domain\Manager\Import\ImportManagerInterface;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Handler\StartProcessImportCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class StartProcessImportCommandHandlerTest extends TestCase
{
    /**
     * @var ImportRepositoryInterface
     */
    private $importRepository;

    /**
     */
    protected function setUp()
    {
        $this->importRepository = $this->createMock(ImportRepositoryInterface::class);
    }

    /**
     */
    public function testHandleWithImportCommand(): void
    {
        $this->importRepository->method('load')->willReturn($this->createMock(AbstractImport::class));
        $this->importRepository->expects($this->once())->method('save');
        /** @var StartProcessImportCommand|MockObject $command */
        $command = $this->createMock(StartProcessImportCommand::class);

        $handler = new StartProcessImportCommandHandler($this->importRepository);

        $handler->__invoke($command);
    }

    /**
     * @expectedException \LogicException
     */
    public function testHandleWithoutImportCommand(): void
    {
        $this->importRepository->method('load')->willReturn(null);
        $this->importRepository->expects($this->never())->method('save');
        /** @var StartProcessImportCommand|MockObject $command */
        $command = $this->createMock(StartProcessImportCommand::class);

        $handler = new StartProcessImportCommandHandler($this->importRepository);

        $handler->__invoke($command);
    }
}
