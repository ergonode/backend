<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Handler\Export;

use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\StartShopware6ExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Handler\Export\StartShopware6ExportCommandHandler;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Process\StartShopware6ExportProcess;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StartShopware6ExportCommandHandlerTest extends TestCase
{
    /**
     * @var ExportRepositoryInterface|MockObject
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var ChannelRepositoryInterface|MockObject
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var StartShopware6ExportProcess|MockObject
     */
    private StartShopware6ExportProcess $processor;

    protected function setUp(): void
    {
        $this->exportRepository = $this->createMock(ExportRepositoryInterface::class);
        $this->exportRepository->method('load')
            ->willReturn($this->createMock(Export::class));
        $this->exportRepository->expects(self::once())->method('load');
        $this->exportRepository->expects(self::once())->method('save');

        $this->channelRepository = $this->createMock(ChannelRepositoryInterface::class);
        $this->channelRepository->method('load')
            ->willReturn($this->createMock(Shopware6Channel::class));

        $this->processor = $this->createMock(StartShopware6ExportProcess::class);
        $this->processor->expects(self::once())->method('process');
    }

    public function testHandling(): void
    {
        $command = $this->createMock(StartShopware6ExportCommand::class);

        $handler = new StartShopware6ExportCommandHandler(
            $this->exportRepository,
            $this->channelRepository,
            $this->processor
        );
        $handler->__invoke($command);
    }
}
