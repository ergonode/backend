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
use Ergonode\ExporterShopware6\Domain\Command\Export\ProductCrossSellingExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Handler\Export\ProductCrossSellingExportCommandHandler;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Process\ProductCrossSellingExportProcess;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductCrossSellingExportCommandHandlerTest extends TestCase
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
     * @var ProductCollectionRepositoryInterface|MockObject
     */
    private ProductCollectionRepositoryInterface $productCollectionRepository;

    /**
     * @var ProductCrossSellingExportProcess|MockObject
     */
    private ProductCrossSellingExportProcess $process;

    protected function setUp(): void
    {
        $this->exportRepository = $this->createMock(ExportRepositoryInterface::class);
        $this->exportRepository->method('load')
            ->willReturn($this->createMock(Export::class));
        $this->exportRepository->expects(self::once())->method('load');

        $this->channelRepository = $this->createMock(ChannelRepositoryInterface::class);
        $this->channelRepository->method('load')
            ->willReturn($this->createMock(Shopware6Channel::class));
        $this->channelRepository->expects(self::once())->method('load');

        $this->productCollectionRepository = $this->createMock(ProductCollectionRepositoryInterface::class);
        $this->productCollectionRepository->method('load')
            ->willReturn($this->createMock(ProductCollection::class));
        $this->productCollectionRepository->expects(self::once())->method('load');

        $this->process = $this->createMock(ProductCrossSellingExportProcess::class);
        $this->process->expects(self::once())->method('process');
    }

    public function testHandling(): void
    {
        $command = $this->createMock(ProductCrossSellingExportCommand::class);

        $handler = new ProductCrossSellingExportCommandHandler(
            $this->exportRepository,
            $this->channelRepository,
            $this->productCollectionRepository,
            $this->process
        );
        $handler->__invoke($command);
    }
}
