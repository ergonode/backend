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
use Ergonode\Importer\Domain\Command\Import\ImportVariableProductCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class Magento1ConfigurableProductProcessor extends AbstractProductProcessor implements Magento1ProcessorStepInterface
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
        if ($product->getType() === 'configurable') {
            $id = ImportLineId::generate();
            $categories = $this->getCategories($product);
            $attributes = $this->getAttributes($source, $product, $attributes);
            $bindings = $this->getBindings($product);
            $variants = $this->getVariants($product);

            $command = new ImportVariableProductCommand(
                $id,
                $import->getId(),
                $product->getSku(),
                $product->getTemplate(),
                $categories,
                $bindings,
                $variants,
                $attributes
            );
            $this->commandBus->dispatch($command, true);
            $this->importRepository->addLine($id, $import->getId(), 'PRODUCT');
        }
    }

    /**
     * @return string[]
     */
    private function getBindings(ProductModel $product): array
    {
        $default = $product->getDefault();
        if ($default['bindings']) {
            return array_unique(explode(',', $default['bindings']));
        }

        return [];
    }

    /**
     * @return string[]
     */
    private function getVariants(ProductModel $product): ?array
    {
        $default = $product->getDefault();
        if ($default['variants']) {
            return array_unique(explode(',', $default['variants']));
        }

        return [];
    }
}
