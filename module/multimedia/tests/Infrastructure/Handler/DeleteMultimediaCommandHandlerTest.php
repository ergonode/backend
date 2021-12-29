<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Command\DeleteMultimediaCommand;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Handler\DeleteMultimediaCommandHandler;
use PHPUnit\Framework\TestCase;

class DeleteMultimediaCommandHandlerTest extends TestCase
{
    public function testHandling(): void
    {
        $command = $this->createMock(DeleteMultimediaCommand::class);
        $repository = $this->createMock(MultimediaRepositoryInterface::class);
        $repository->method('load')
            ->willReturn($this->createMock(Multimedia::class));
        $repository->expects(self::once())->method('load');
        $repository->expects(self::once())->method('delete');

        $handler = new DeleteMultimediaCommandHandler($repository);
        $handler->__invoke($command);
    }
}
