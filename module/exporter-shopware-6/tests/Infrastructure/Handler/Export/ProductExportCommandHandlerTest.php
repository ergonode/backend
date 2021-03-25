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
use Ergonode\ExporterShopware6\Domain\Command\Export\ProductExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Handler\Export\ProductExportCommandHandler;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Process\ProductShopware6ExportProcess;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductExportCommandHandlerTest extends TestCase
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
     * @var ProductRepositoryInterface|MockObject
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ProductShopware6ExportProcess|MockObject
     */
    private ProductShopware6ExportProcess $process;

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

        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->productRepository->method('load')
            ->willReturn($this->createMock(AbstractProduct::class));
        $this->productRepository->expects(self::once())->method('load');

        $this->process = $this->createMock(ProductShopware6ExportProcess::class);
        $this->process->expects(self::once())->method('process');
    }

    public function testHandling(): void
    {
        $command = $this->createMock(ProductExportCommand::class);

        $handler = new ProductExportCommandHandler(
            $this->exportRepository,
            $this->channelRepository,
            $this->productRepository,
            $this->process
        );
        $handler->__invoke($command);
    }
}
