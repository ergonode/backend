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
use Ergonode\Importer\Domain\Command\Import\ImportVariableProductCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Domain\ValueObject\Sku;

class Magento1ConfigurableProductProcessor extends AbstractProductProcessor implements Magento1ProcessorStepInterface
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
        if ($product->getType() === 'configurable') {
            $categories = $this->getCategories($product);
            $attributes = $this->getAttributes($transformer, $source, $product);
            $bindings = $this->getBindings($product);
            $variants = $this->getVariants($product);

            $command = new ImportVariableProductCommand(
                $import->getId(),
                $product->getSku(),
                $product->getTemplate(),
                $categories,
                $bindings,
                $variants,
                $attributes
            );
            $this->commandBus->dispatch($command, true);
        }
    }

    /**
     * @param ProductModel $product
     *
     * @return AttributeCode[]
     */
    private function getBindings(ProductModel $product): array
    {
        $result = [];

        $default = $product->get('default');
        if ($bindings = $default['bindings'] ?? null) {
            foreach (explode(',', $bindings) as $binding) {
                $result[] = new AttributeCode($binding);
            }
        }

        return $result;
    }

    /**
     * @param ProductModel $product
     *
     * @return Sku[]
     */
    private function getVariants(ProductModel $product): ?array
    {
        $result = [];

        $default = $product->get('default');
        if ($variants = $default['variants'] ?? null) {
            foreach (explode(',', $variants) as $variant) {
                $result[] = new Sku($variant);
            }
        }

        return $result;
    }
}
