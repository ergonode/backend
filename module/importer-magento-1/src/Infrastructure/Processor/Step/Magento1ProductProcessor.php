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
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Transformer\Infrastructure\Action\ProductImportAction;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;

/**
 */
class Magento1ProductProcessor implements Magento1ProcessorStepInterface
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
     * @param Import         $import
     * @param ProductModel[] $products
     * @param Transformer    $transformer
     * @param Language       $defaultLanguage
     */
    public function process(Import $import, array $products, Transformer $transformer, Language $defaultLanguage): void
    {
        $i = 0;
        $products = $this->getGroupedProducts($products);
        /** @var ProductModel $product */
        foreach ($products['simple'] as $product) {
            $i++;
            $record = $this->getRecord($product, $transformer);
            $command = new ProcessImportCommand($import->getId(), $i, $record, ProductImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }

        echo print_r(sprintf('SEND %s Products', $i), true).PHP_EOL;
    }

    /**
     * @param ProductModel[] $products
     *
     * @return array
     */
    private function getGroupedProducts(array $products): array
    {
        $result = [];
        foreach ($products as $product) {
            $type = $product->get('default')['esa_type'];
            $result[$type][] = $product;

        }

        return $result;
    }

    /**
     * @param ProductModel $product
     * @param Transformer  $transformer
     *
     * @return Record
     */
    public function getRecord(ProductModel $product, Transformer $transformer): Record
    {
        $default = $product->get('default');

        $record = new Record();

        foreach ($default as $field => $value) {
            if ($transformer->hasAttribute($field)) {
                $type = $transformer->getAttributeType($field);
                if (null === $value) {
                    $record->setValue($field, null);
                } else {
                    if ($type !== SelectAttribute::TYPE && $type !== MultiSelectAttribute::TYPE) {
                        $record->setValue($field,
                            new TranslatableStringValue(new TranslatableString([Language::EN => $value])));
                    } else {
                        $record->setValue($field, new Stringvalue($value));
                    }
                }
            }

            if ($transformer->hasField($field)) {
                $record->set($field, new StringValue($value));
            }
        }
        return $record;
    }
}
