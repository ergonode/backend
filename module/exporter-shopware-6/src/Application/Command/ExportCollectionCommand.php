<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Application\Command;

use Ergonode\ExporterShopware6\Domain\Command\Export\EndShopware6ExportCommand;
use Ergonode\ExporterShopware6\Domain\Command\Export\ProductCrossSellingExportCommand;
use Ergonode\ExporterShopware6\Domain\Command\Export\ProductExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCollectionCommand extends Command
{
    /**
     * @var mixed
     */
    protected static $defaultName = 'test:export:shopware-product-collection';

    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $exportId = new ExportId('e7de179b-f31b-46af-ba92-c461f85272c0');

        $command = new EndShopware6ExportCommand($exportId);
            $this->product($exportId);
        $this->productCollection($exportId);

        $this->commandBus->dispatch($command);
    }

    private function productCollection(ExportId $exportId): ProductCrossSellingExportCommand
    {
        $productCollectionId = new ProductCollectionId('55c36e2e-0f9f-45e5-8a19-4890ddd3bf53');

        return new ProductCrossSellingExportCommand($exportId, $productCollectionId);
    }

    private function product(ExportId $exportId): ProductExportCommand
    {
        $productId = new ProductId('637e5273-0d64-4d03-93bd-f411d8f7ad24');
//        $productId = new ProductId('69a4ee28-8600-46d3-a47b-4362e52b4cd4');
//        $productId = new ProductId('ece1099e-7537-45f2-9c87-85b0c2dd70ce');

        return new ProductExportCommand($exportId, $productId);
    }
}
