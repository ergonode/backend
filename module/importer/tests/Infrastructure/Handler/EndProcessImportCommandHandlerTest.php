<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Importer\Tests\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\EndProcessImportCommand;
use Ergonode\Importer\Domain\Entity\AbstractImport;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Handler\EndProcessImportCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 */
class EndProcessImportCommandHandlerTest extends TestCase
{
    /**
     * @var ImportRepositoryInterface
     */
    private $importRepository;

    /**
     * @var MessageBusInterface|MockObject
     */
    private $messageBus;

    /**
     */
    protected function setUp()
    {
        $this->importRepository = $this->createMock(ImportRepositoryInterface::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);
    }

    /**
     */
    public function testHandleWithImportCommand(): void
    {
        $this->importRepository->method('load')->willReturn($this->createMock(AbstractImport::class));
        $this->importRepository->expects($this->once())->method('save');
        /** @var EndProcessImportCommand|MockObject $command */
        $command = $this->createMock(EndProcessImportCommand::class);

        $handler = new EndProcessImportCommandHandler($this->importRepository, $this->messageBus);

        $handler->__invoke($command);
    }

    /**
     * @expectedException \LogicException
     */
    public function testHandleWithoutImportCommand(): void
    {
        $this->importRepository->method('load')->willReturn(null);
        $this->importRepository->expects($this->never())->method('save');
        /** @var EndProcessImportCommand|MockObject $command */
        $command = $this->createMock(EndProcessImportCommand::class);

        $handler = new EndProcessImportCommandHandler($this->importRepository, $this->messageBus);

        $handler->__invoke($command);
    }
}
