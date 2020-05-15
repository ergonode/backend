<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Handler\Export;

use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Core\Application\Exception\NotImplementedException;
use Ergonode\Exporter\Domain\Command\Export\StartExportCommand;
use Ergonode\Exporter\Domain\Repository\ChannelConfigurationRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Exporter\Infrastructure\Provider\ExportProcessorProvider;
use Webmozart\Assert\Assert;

/**
 */
class StartExportCommandHandler
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
     * @var ChannelConfigurationRepositoryInterface
     */
    private ChannelConfigurationRepositoryInterface $channelConfigurationRepository;

    /**
     * @var ExportProfileRepositoryInterface
     */
    private ExportProfileRepositoryInterface $exportProfileRepository;

    /**
     * @var ExportProcessorProvider
     */
    private ExportProcessorProvider $provider;

    /**
     * @param ExportRepositoryInterface               $exportRepository
     * @param ChannelRepositoryInterface              $channelRepository
     * @param ChannelConfigurationRepositoryInterface $channelConfigurationRepository
     * @param ExportProfileRepositoryInterface        $exportProfileRepository
     * @param ExportProcessorProvider                 $provider
     */
    public function __construct(
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        ChannelConfigurationRepositoryInterface $channelConfigurationRepository,
        ExportProfileRepositoryInterface $exportProfileRepository,
        ExportProcessorProvider $provider
    ) {
        $this->exportRepository = $exportRepository;
        $this->channelRepository = $channelRepository;
        $this->channelConfigurationRepository = $channelConfigurationRepository;
        $this->exportProfileRepository = $exportProfileRepository;
        $this->provider = $provider;
    }


    /**
     * @param StartExportCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(StartExportCommand $command)
    {
        throw new NotImplementedException();
        //todo change after udate channel
        $export = $this->exportRepository->load($command->getExportId());
        Assert::notNull($export);
        $channel = $this->channelRepository->load($export->getChannelId());
        Assert::notNull($channel);
        $channelConfiguration = $this->channelConfigurationRepository->load($export->getChannelId());
        Assert::notNull($channelConfiguration);
        $exportProfile = $this->exportProfileRepository->load($channel->getExportProfileId());
        Assert::notNull($exportProfile);

        $export->start();
        $this->exportRepository->save($export);

        $processor = $this->provider->provide($exportProfile->getType());
        $processor->run($export);
    }
}
