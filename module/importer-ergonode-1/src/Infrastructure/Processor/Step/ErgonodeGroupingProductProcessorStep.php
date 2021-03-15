<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor\Step;

use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeProductReader;
use Ergonode\ImporterErgonode1\Infrastructure\Resolver\ProductCommandResolver;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Product\Domain\Entity\GroupingProduct;

class ErgonodeGroupingProductProcessorStep implements ErgonodeProcessorStepInterface
{
    private const FILENAME = 'products.csv';

    private CommandBusInterface $commandBus;
    private ProductCommandResolver $commandResolver;
    private ImportRepositoryInterface $importRepository;

    public function __construct(
        CommandBusInterface $commandBus,
        ProductCommandResolver $commandResolver,
        ImportRepositoryInterface $importRepository
    ) {
        $this->commandBus = $commandBus;
        $this->commandResolver = $commandResolver;
        $this->importRepository = $importRepository;
    }

    public function __invoke(Import $import, ErgonodeZipSource $source, string $directory): void
    {
        if (!$source->import(ErgonodeZipSource::PRODUCTS)) {
            return;
        }

        $reader = new ErgonodeProductReader($directory, self::FILENAME);

        while ($product = $reader->read()) {
            if ($product->getType() === GroupingProduct::TYPE) {
                $id = ImportLineId::generate();
                $command = $this->commandResolver->resolve($id, $import, $product);
                $this->importRepository->addLine($id, $import->getId(), 'PRODUCT');
                $this->commandBus->dispatch($command, true);
            }
        }
    }
}
