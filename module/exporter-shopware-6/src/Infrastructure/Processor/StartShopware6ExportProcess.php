<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor;

use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Exporter\Domain\Entity\Catalog\AbstractExportProduct;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Domain\Repository\ChannelConfigurationRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\Exporter\Infrastructure\Provider\ExportProcessorInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ChannelApiProfile;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\ExportProfile\Shopware6ExportProfile;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Step\Shopware6CategoryTreeProcessor;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Webmozart\Assert\Assert;

/**
 */
class StartShopware6ExportProcess implements ExportProcessorInterface
{
    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var ExportProfileRepositoryInterface
     */
    private ExportProfileRepositoryInterface $exportProfileRepository;

    /**
     * @var ChannelConfigurationRepositoryInterface
     */
    private ChannelConfigurationRepositoryInterface $channelConfigurationRepository;

    private Shopware6CategoryTreeProcessor $categoryTree;

    /**
     * @param ChannelRepositoryInterface              $channelRepository
     * @param ExportProfileRepositoryInterface        $exportProfileRepository
     * @param ChannelConfigurationRepositoryInterface $channelConfigurationRepository
     * @param Shopware6CategoryTreeProcessor          $categoryTree
     */
    public function __construct(
        ChannelRepositoryInterface $channelRepository,
        ExportProfileRepositoryInterface $exportProfileRepository,
        ChannelConfigurationRepositoryInterface $channelConfigurationRepository,
        Shopware6CategoryTreeProcessor $categoryTree
    ) {
        $this->channelRepository = $channelRepository;
        $this->exportProfileRepository = $exportProfileRepository;
        $this->channelConfigurationRepository = $channelConfigurationRepository;
        $this->categoryTree = $categoryTree;
    }


    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return Shopware6ExportApiProfile::TYPE === $type;
    }

    /**
     * @param Export $export
     *
     * @throws \ReflectionException
     */
    public function run(Export $export): void
    {
        $channel = $this->channelRepository->load($export->getChannelId());
        Assert::notNull($channel);
        $channelConfiguration = $this->channelConfigurationRepository->load($export->getChannelId());
        Assert::notNull($channelConfiguration);
        $exportProfile = $this->exportProfileRepository->load($channel->getExportProfileId());
        Assert::notNull($exportProfile);


        $this->categoryTree->process($export, $exportProfile, $channelConfiguration);
    }
}
