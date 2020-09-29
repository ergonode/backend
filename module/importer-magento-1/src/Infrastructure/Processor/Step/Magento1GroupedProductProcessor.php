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

/**
 */
class Magento1GroupedProductProcessor extends AbstractProductProcessor implements Magento1ProcessorStepInterface
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param Import            $import
     * @param ProductModel      $product
     * @param Transformer       $transformer
     * @param Magento1CsvSource $source
     */
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
     * @param ProductModel $product
     *
     * @return Sku[]
     */
    private function getChildren(ProductModel $product): array
    {
        $result = [];

        $default = $product->get('default');
        if (array_key_exists('relations', $default) && null !== $default['relations']) {
            $variants = explode(',', $default['relations']);
            foreach ($variants as $variant) {
                $result[] = new Sku($variant);
            }
        }

        return $result;
    }
}
