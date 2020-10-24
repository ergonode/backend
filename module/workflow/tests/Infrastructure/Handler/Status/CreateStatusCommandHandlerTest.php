<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Handler\Status;

use Ergonode\Workflow\Infrastructure\Handler\Status\CreateStatusCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Domain\Factory\StatusFactory;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Domain\Command\Status\CreateStatusCommand;

class CreateStatusCommandHandlerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandHandling(): void
    {
        $command = $this->createMock(CreateStatusCommand::class);

        $repository = $this->createMock(StatusRepositoryInterface::class);
        $repository->expects(self::once())->method('save');
        $factory = $this->createMock(StatusFactory::class);
        $factory->expects(self::once())->method('create');

        $handler = new CreateStatusCommandHandler($repository, $factory);
        $handler->__invoke($command);
    }
}
