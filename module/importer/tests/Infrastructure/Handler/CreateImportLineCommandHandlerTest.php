<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Importer\Tests\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\ProcessImportLineCommand;
use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Ergonode\Importer\Infrastructure\Handler\ProcessLineCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateImportLineCommandHandlerTest extends TestCase
{
    /**
     * @var ImportLineRepositoryInterface|MockObject
     */
    private $repository;

    /**
     */
    protected function setUp()
    {
        $this->repository = $this->createMock(ImportLineRepositoryInterface::class);
        $this->repository->expects($this->once())->method('save');
    }

    /**
     */
    public function testHandleCommand(): void
    {
        /** @var ProcessImportLineCommand|MockObject $command */
        $command = $this->createMock(ProcessImportLineCommand::class);
        $command->expects($this->once())->method('getCollection')->willReturn([]);

        $handler = new ProcessLineCommandHandler($this->repository);

        $handler->__invoke($command);
    }
}
