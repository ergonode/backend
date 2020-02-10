<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Transformer\Infrastructure\Action\ProductImportAction;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Provider\ConverterMapperProvider;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;

/**
 */
class Magento1ProductProcessor implements Magento1ProcessorStepInterface
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var ConverterMapperProvider
     */
    private ConverterMapperProvider $mapperProvider;

    /**
     * @param CommandBusInterface     $commandBus
     * @param ConverterMapperProvider $mapperProvider
     */
    public function __construct(CommandBusInterface $commandBus, ConverterMapperProvider $mapperProvider)
    {
        $this->commandBus = $commandBus;
        $this->mapperProvider = $mapperProvider;
    }

    /**
     * @param ImportId $id
     * @param string[] $rows
     * @param Language $language
     */
    public function process(ImportId $id, array $rows, Language $language): void
    {
        $i = 0;
        $products = $this->getGroupedProducts($rows);
        foreach ($products['simple'] as $key => $product) {
            $product = reset($product);
            $i++;
            $record = $this->transform($product, $transformer);
            $command = new ProcessImportCommand($id, $i, $record, ProductImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }
    }

    /**
     * @param array $rows
     *
     * @return array
     */
    private function getGroupedProducts(array $rows): array
    {
        $products['simple'] = [];
        $products['configurable'] = [];
        $products['bundle'] = [];
        $sku = null;
        $type = null;

        foreach ($rows as $row) {
            if ($row['sku'] !== null && $row['sku'] !== '') {
                $sku = $row['sku'];
                $type = $row['_type'];
            }

            if ($type && $sku) {
                $products[$type][$sku][] = $row;
            }
        }

        return $products;
    }

    /**
     * @param array       $row
     * @param Transformer $transformer
     *
     * @return Record
     */
    public function transform(array $row, Transformer $transformer): Record
    {
        $record = new Record();
        foreach ($transformer->getConverters() as $collection => $converters) {
            /** @var ConverterInterface $converter */
            foreach ($converters as $field => $converter) {
                $mapper = $this->mapperProvider->provide($converter);
                $value = $mapper->map($converter, $row);
                if ($collection === 'values') {
                    $record->setValue($field, $value);
                } else {
                    $record->set($field, $value);
                }
            }
        }

        return $record;
    }
}
