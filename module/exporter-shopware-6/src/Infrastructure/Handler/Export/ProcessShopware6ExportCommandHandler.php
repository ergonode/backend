<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler\Export;

use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\ProcessShopware6ExportCommand;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Process\ProcessShopware6ExportProcess;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class ProcessShopware6ExportCommandHandler
{
    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ProcessShopware6ExportProcess
     */
    private ProcessShopware6ExportProcess $process;

    /**
     * @param ExportRepositoryInterface     $exportRepository
     * @param ChannelRepositoryInterface    $channelRepository
     * @param ProductRepositoryInterface    $productRepository
     * @param ProcessShopware6ExportProcess $process
     */
    public function __construct(
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        ProductRepositoryInterface $productRepository,
        ProcessShopware6ExportProcess $process
    ) {
        $this->exportRepository = $exportRepository;
        $this->channelRepository = $channelRepository;
        $this->productRepository = $productRepository;
        $this->process = $process;
    }

    /**
     * @param ProcessShopware6ExportCommand $command
     */
    public function __invoke(ProcessShopware6ExportCommand $command)
    {
        $export  = $this->exportRepository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $channel = $this->channelRepository->load($export->getChannelId());
        Assert::isInstanceOf($channel, Shopware6Channel::class);
        $product = $this->productRepository->load($command->getProductId());
        Assert::isInstanceOf($product, AbstractProduct::class);

        $this->process->process($export->getId(), $channel, $product);
    }
}
