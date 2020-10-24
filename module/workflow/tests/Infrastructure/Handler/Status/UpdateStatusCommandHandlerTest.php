<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\Handler\Status;

use Ergonode\Workflow\Infrastructure\Handler\Status\UpdateStatusCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Domain\Command\Status\UpdateStatusCommand;
use Ergonode\Workflow\Domain\Entity\Status;

class UpdateStatusCommandHandlerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandHandling(): void
    {
        $command = $this->createMock(UpdateStatusCommand::class);
        $status = $this->createMock(Status::class);

        $repository = $this->createMock(StatusRepositoryInterface::class);
        $repository->expects(self::once())->method('load')->willReturn($status);
        $repository->expects(self::once())->method('save');

        $handler = new UpdateStatusCommandHandler($repository);
        $handler->__invoke($command);
    }
}
