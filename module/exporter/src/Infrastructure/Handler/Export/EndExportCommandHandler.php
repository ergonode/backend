<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Handler\Export;

use Ergonode\Exporter\Domain\Command\Export\EndExportCommand;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Exporter\Infrastructure\Provider\ExportProcessorProvider;
use Webmozart\Assert\Assert;

/**
 */
class EndExportCommandHandler
{
    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var ExportProfileRepositoryInterface
     */
    private ExportProfileRepositoryInterface $exportProfileRepository;

    /**
     * @var ExportProcessorProvider
     */
    private ExportProcessorProvider $provider;

    /**
     * @param ExportRepositoryInterface        $exportRepository
     * @param ExportProfileRepositoryInterface $exportProfileRepository
     * @param ExportProcessorProvider          $provider
     */
    public function __construct(
        ExportRepositoryInterface $exportRepository,
        ExportProfileRepositoryInterface $exportProfileRepository,
        ExportProcessorProvider $provider
    ) {
        $this->exportRepository = $exportRepository;
        $this->exportProfileRepository = $exportProfileRepository;
        $this->provider = $provider;
    }

    /**
     * @param EndExportCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(EndExportCommand $command)
    {
        $export = $this->exportRepository->load($command->getExportId());
        Assert::notNull($export);
        $exportProfile = $this->exportProfileRepository->load($export->getExportProfileId());
        Assert::notNull($exportProfile);

        $export->end();
        $this->exportRepository->save($export);

        $processor = $this->provider->provide($exportProfile->getType());
        $processor->end($exportProfile);
    }
}
