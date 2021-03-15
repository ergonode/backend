<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Handler;

use Ergonode\ExporterShopware6\Domain\Command\CreateShopware6ChannelCommand;
use Ergonode\ExporterShopware6\Infrastructure\Handler\CreateShopware6ChannelCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;

class CreateShopware6ChannelCommandHandlerTest extends TestCase
{
    public function testHandling(): void
    {
        $command = $this->createMock(CreateShopware6ChannelCommand::class);
        $repository = $this->createMock(ChannelRepositoryInterface::class);
        $repository->expects(self::once())->method('save');

        $handler = new CreateShopware6ChannelCommandHandler($repository);
        $handler->__invoke($command);
    }
}
