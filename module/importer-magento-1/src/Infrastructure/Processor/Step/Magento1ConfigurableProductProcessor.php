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
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Importer\Infrastructure\Action\VariableProductImportAction;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;

/**
 */
class Magento1ConfigurableProductProcessor extends AbstractProductProcessor implements Magento1ProcessorStepInterface
{
    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $attributeQuery;

    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param AttributeQueryInterface $attributeQuery
     * @param ProductQueryInterface   $productQuery
     * @param CommandBusInterface     $commandBus
     */
    public function __construct(
        AttributeQueryInterface $attributeQuery,
        ProductQueryInterface $productQuery,
        CommandBusInterface $commandBus
    ) {
        $this->attributeQuery = $attributeQuery;
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
        if ($product->getType() === 'configurable') {
            $record = $this->getRecord($transformer, $source, $product);

            $default = $product->get('default');

            if ($bindings = $this->getBindings($default)) {
                $record->set('bindings', $bindings);
            }

            if ($variants = $this->getVariants($default)) {
                $record->set('variants', $variants);
            }

            $command = new ProcessImportCommand(
                $import->getId(),
                $record,
                VariableProductImportAction::TYPE
            );
            $this->commandBus->dispatch($command, true);
        }
    }

    /**
     * @param array $default
     *
     * @return null|string
     */
    private function getBindings(array $default): ?string
    {
        $result = [];
        $bindings = [];

        if (array_key_exists('bindings', $default) && $default['bindings'] !== null) {
            $bindings = explode(',', $default['bindings']);
            $bindings = array_unique($bindings);
        }

        foreach ($bindings as $binding) {
            $code = new AttributeCode($binding);
            $model = $this->attributeQuery->findAttributeByCode($code);
            Assert::notNull($model, sprintf('Can\'t find attribute %s for binding', $code));
            $result[] = $model->getId()->getValue();
        }

        if (!empty($result)) {
            return implode(',', $result);
        }

        return null;
    }

    /**
     * @param array $default
     *
     * @return null|string
     */
    private function getVariants(array $default): ?string
    {
        $result = [];

        if (array_key_exists('variants', $default) && null !== $default['variants']) {
            $variants = explode(',', $default['variants']);
            $variants = array_unique($variants);

            foreach ($variants as $variant) {
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
