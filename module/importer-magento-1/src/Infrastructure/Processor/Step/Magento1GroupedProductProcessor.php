<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Importer\Infrastructure\Action\GroupedProductImportAction;

/**
 */
class Magento1GroupedProductProcessor extends AbstractProductProcessor implements Magento1ProcessorStepInterface
{
    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ProductQueryInterface $productQuery
     * @param CommandBusInterface   $commandBus
     */
    public function __construct(
        ProductQueryInterface $productQuery,
        CommandBusInterface $commandBus
    ) {
        $this->productQuery = $productQuery;
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
            $record = $this->getRecord($transformer, $source, $product);

            $default = $product->get('default');

            if ($children = $this->getChildren($default)) {
                $record->set('children', $children);
            }

            $command = new ProcessImportCommand(
                $import->getId(),
                $record,
                GroupedProductImportAction::TYPE
            );
            $this->commandBus->dispatch($command, true);
        }
    }

    /**
     * @param array $default
     *
     * @return null|string
     */
    private function getChildren(array $default): ?string
    {
        $result = [];

        if (array_key_exists('relations', $default)) {
            $children = explode(',', $default['relations']);
            $children = array_unique($children);

            foreach ($children as $variant) {
                $sku = new Sku($variant);
                $productId = $this->productQuery->findProductIdBySku($sku);
                if ($productId) {
                    $result[] = $productId->getValue();
                }
            }
        }

        if (!empty($result)) {
            return implode(',', $result);
        }

        return null;
    }
}
