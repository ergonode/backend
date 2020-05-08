<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler\Export;

use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\ChannelConfigurationRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\Shopware6ExportTreeCommand;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\CategoryTreeMapper;
use Ergonode\ExporterShopware6\Infrastructure\Synchronize\CategorySynchronize;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 */
class Shopware6ExportTreeCommandHandler
{
    /**
     * @var CategoryTreeMapper
     */
    private CategoryTreeMapper $mapper;

    /**
     * @var CategorySynchronize
     */
    private CategorySynchronize $synchronize;

    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var ChannelConfigurationRepositoryInterface
     */
    private ChannelConfigurationRepositoryInterface $channelConfigurationRepository;

    /**
     * @var ExportProfileRepositoryInterface
     */
    private ExportProfileRepositoryInterface $exportProfileRepository;

    /**
     * @param CategoryTreeMapper                      $mapper
     * @param CategorySynchronize                     $synchronize
     * @param ExportRepositoryInterface               $exportRepository
     * @param ChannelRepositoryInterface              $channelRepository
     * @param ChannelConfigurationRepositoryInterface $channelConfigurationRepository
     * @param ExportProfileRepositoryInterface        $exportProfileRepository
     */
    public function __construct(
        CategoryTreeMapper $mapper,
        CategorySynchronize $synchronize,
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        ChannelConfigurationRepositoryInterface $channelConfigurationRepository,
        ExportProfileRepositoryInterface $exportProfileRepository
    ) {
        $this->mapper = $mapper;
        $this->synchronize = $synchronize;
        $this->exportRepository = $exportRepository;
        $this->channelRepository = $channelRepository;
        $this->channelConfigurationRepository = $channelConfigurationRepository;
        $this->exportProfileRepository = $exportProfileRepository;
    }


    /**
     * @param Shopware6ExportTreeCommand $command
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \ReflectionException
     */
    public function __invoke(Shopware6ExportTreeCommand $command)
    {
        $export = $this->exportRepository->load($command->getExportId());
        Assert::notNull($export);
        $channel = $this->channelRepository->load($export->getChannelId());
        Assert::notNull($channel);
        $channelConfiguration = $this->channelConfigurationRepository->load($export->getChannelId());
        Assert::notNull($channelConfiguration);
        $exportProfile = $this->exportProfileRepository->load($channel->getExportProfileId());
        Assert::notNull($exportProfile);


        $treeId = Uuid::fromString($channelConfiguration->getCategoryTreeId()->getValue());

        $this->mapper->map($exportProfile, $treeId);

        $this->synchronize->synchronize($exportProfile, $treeId);
    }
}
