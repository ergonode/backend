<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Importer\Domain\Command\Import\ImportGroupingProductCommand;
use Ergonode\Product\Domain\ValueObject\Sku;

class Magento1GroupedProductProcessor extends AbstractProductProcessor implements Magento1ProcessorStepInterface
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function process(
        Import $import,
        ProductModel $product,
        Transformer $transformer,
        Magento1CsvSource $source
    ): void {
        if ($product->getType() === 'grouped') {
            $categories = $this->getCategories($product);
            $children = $this->getChildren($product);
            $attributes = $this->getAttributes($transformer, $source, $product);

            $command = new ImportGroupingProductCommand(
                $import->getId(),
                $product->getSku(),
                $product->getTemplate(),
                $categories,
                $children,
                $attributes
            );
            $this->commandBus->dispatch($command, true);
        }
    }

    /**
     * @return Sku[]
     */
    private function getChildren(ProductModel $product): array
    {
        $result = [];

        $default = $product->get('default');
        if ($relations = $default['relations'] ?? null) {
            foreach (explode(',', $relations) as $relation) {
                $result[] = new Sku($relation);
            }
        }

        return $result;
    }
}
