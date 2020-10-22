<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Infrastructure\Handler\Tree;

use Ergonode\Category\Domain\Command\Tree\CreateTreeCommand;
use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Ergonode\Category\Infrastructure\Handler\Tree\CreateTreeCommandHandler;
use PHPUnit\Framework\TestCase;

class CreateTreeCommandHandlerTest extends TestCase
{
    public function testHandling(): void
    {
        $repository = $this->createMock(TreeRepositoryInterface::class);
        $repository->expects($this->once())->method('save');

        $command = $this->createMock(CreateTreeCommand::class);
        $handler = new CreateTreeCommandHandler($repository);

        $handler->__invoke($command);
    }
}
