<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Importer\Domain\Command\Import\ImportGroupingProductCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;

class Magento1GroupedProductProcessor extends AbstractProductProcessor implements Magento1ProcessorStepInterface
{
    private CommandBusInterface $commandBus;

    private ImportRepositoryInterface $importRepository;

    public function __construct(CommandBusInterface $commandBus, ImportRepositoryInterface $importRepository)
    {
        $this->commandBus = $commandBus;
        $this->importRepository = $importRepository;
    }

    /**
     * @param AbstractAttribute[] $attributes
     */
    public function process(
        Import $import,
        ProductModel $product,
        Magento1CsvSource $source,
        array $attributes
    ): void {
        if ($product->getType() === 'grouped') {
            $id = ImportLineId::generate();
            $categories = $this->getCategories($product);
            $children = $this->getChildren($product);
            $attributes = $this->getAttributes($source, $product, $attributes);

            $command = new ImportGroupingProductCommand(
                $id,
                $import->getId(),
                $product->getSku(),
                $product->getTemplate(),
                $categories,
                $children,
                $attributes
            );
            $this->importRepository->addLine($id, $import->getId(), 'PRODUCT');
            $this->commandBus->dispatch($command, true);
        }
    }

    /**
     * @return string[]
     */
    private function getChildren(ProductModel $product): array
    {
        $default = $product->getDefault();

        if ($relations = $default['relations'] ?? null) {
            return explode(',', $relations);
        }

        return [];
    }
}
