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
use Ergonode\Exporter\Domain\Repository\ExportLineRepositoryInterface;
use Ergonode\Exporter\Domain\Entity\ExportLine;
use Doctrine\DBAL\DBALException;

/**
 */
class ProcessExportCommandHandler
{
    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var ExportLineRepositoryInterface
     */
    private ExportLineRepositoryInterface $lineRepository;

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
     * @param ExportLineRepositoryInterface    $lineRepository
     * @param ExportProfileRepositoryInterface $exportProfileRepository
     * @param ProductRepositoryInterface       $productRepository
     * @param ExportProcessorProvider          $provider
     */
    public function __construct(
        ExportRepositoryInterface $exportRepository,
        ExportLineRepositoryInterface $lineRepository,
        ExportProfileRepositoryInterface $exportProfileRepository,
        ProductRepositoryInterface $productRepository,
        ExportProcessorProvider $provider
    ) {
        $this->exportRepository = $exportRepository;
        $this->lineRepository = $lineRepository;
        $this->exportProfileRepository = $exportProfileRepository;
        $this->productRepository = $productRepository;
        $this->provider = $provider;
    }

    /**
     * @param ProcessExportCommand $command
     *
     * @throws \ReflectionException
     * @throws DBALException
     */
    public function __invoke(ProcessExportCommand $command)
    {
        $export = $this->exportRepository->load($command->getExportId());
        Assert::notNull($export);
        $exportProfile = $this->exportProfileRepository->load($export->getExportProfileId());
        Assert::notNull($exportProfile);
        $product = $this->productRepository->load($command->getProductId());
        Assert::notNull($product);

        $line = new ExportLine($export->getId(), $product->getId());
        try {
            $processor = $this->provider->provide($exportProfile->getType());
            $processor->process($command->getExportId(), $exportProfile, $product);
            $line->process();
        } catch (\Exception $exception) {
            $line->addError($exception->getMessage());
        }

        $this->lineRepository->save($line);
    }
}
