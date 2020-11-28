<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler\Export;

use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\ProductShopware6ExportCommand;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Process\ProductShopware6ExportProcess;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class ProductShopware6ExportCommandHandler
{
    private ExportRepositoryInterface $exportRepository;

    private ChannelRepositoryInterface $channelRepository;

    private ProductRepositoryInterface $productRepository;

    private ProductShopware6ExportProcess $process;

    public function __construct(
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        ProductRepositoryInterface $productRepository,
        ProductShopware6ExportProcess $process
    ) {
        $this->exportRepository = $exportRepository;
        $this->channelRepository = $channelRepository;
        $this->productRepository = $productRepository;
        $this->process = $process;
    }

    public function __invoke(ProductShopware6ExportCommand $command): void
    {
        $export  = $this->exportRepository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $channel = $this->channelRepository->load($export->getChannelId());
        Assert::isInstanceOf($channel, Shopware6Channel::class);
        $product = $this->productRepository->load($command->getProductId());
        Assert::isInstanceOf($product, AbstractProduct::class);

        $this->process->process($export, $channel, $product);
    }
}
