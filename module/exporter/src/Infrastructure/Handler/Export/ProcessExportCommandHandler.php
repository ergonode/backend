<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Handler\Export;

use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Exporter\Infrastructure\Provider\ExportProcessorProvider;
use Webmozart\Assert\Assert;
use Ergonode\Exporter\Domain\Command\Export\ProcessExportCommand;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;

/**
 */
class ProcessExportCommandHandler
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
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ExportProcessorProvider
     */
    private ExportProcessorProvider $provider;

    /**
     * @param ExportRepositoryInterface        $exportRepository
     * @param ExportProfileRepositoryInterface $exportProfileRepository
     * @param ProductRepositoryInterface       $productRepository
     * @param ExportProcessorProvider          $provider
     */
    public function __construct(
        ExportRepositoryInterface $exportRepository,
        ExportProfileRepositoryInterface $exportProfileRepository,
        ProductRepositoryInterface $productRepository,
        ExportProcessorProvider $provider
    ) {
        $this->exportRepository = $exportRepository;
        $this->exportProfileRepository = $exportProfileRepository;
        $this->productRepository = $productRepository;
        $this->provider = $provider;
    }

    /**
     * @param ProcessExportCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(ProcessExportCommand $command)
    {
        $export = $this->exportRepository->load($command->getExportId());
        Assert::notNull($export);
        $exportProfile = $this->exportProfileRepository->load($export->getExportProfileId());
        Assert::notNull($exportProfile);
        $product = $this->productRepository->load($command->getProductId());
        Assert::notNull($product);

        $processor = $this->provider->provide($exportProfile->getType());
        $processor->process($command->getExportId(), $exportProfile, $product);
    }
}
