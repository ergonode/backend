<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Importer\Tests\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\CreateImportLineCommand;
use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Ergonode\Importer\Infrastructure\Handler\CreateImportLineCommandHandler;
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
        /** @var CreateImportLineCommand|MockObject $command */
        $command = $this->createMock(CreateImportLineCommand::class);
        $command->expects($this->once())->method('getCollection')->willReturn([]);

        $handler = new CreateImportLineCommandHandler($this->repository);

        $handler->__invoke($command);
    }
}
