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
use Ergonode\Importer\Domain\ValueObject\Progress;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Ergonode\Importer\Domain\Entity\ImportLine;
use Doctrine\DBAL\DBALException;
use Ergonode\Importer\Infrastructure\Action\OptionImportAction;
use Ergonode\Value\Domain\ValueObject\StringValue;

/**
 */
class Magento1OptionProcessor implements Magento1ProcessorStepInterface
{
    /**
     * @var ImportLineRepositoryInterface
     */
    private ImportLineRepositoryInterface $repository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ImportLineRepositoryInterface $repository
     * @param CommandBusInterface           $commandBus
     */
    public function __construct(ImportLineRepositoryInterface $repository, CommandBusInterface $commandBus)
    {
        $this->repository = $repository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param Import            $import
     * @param ProductModel[]    $products
     * @param Transformer       $transformer
     * @param Magento1CsvSource $source
     * @param Progress          $steps
     *
     * @throws DBALException
     */
    public function process(
        Import $import,
        array $products,
        Transformer $transformer,
        Magento1CsvSource $source,
        Progress $steps
    ): void {
        $result = [];
        $columns = [];
        foreach ($products as $product) {
            foreach ($product->get('default') as $key => $item) {
                if ('_' !== $key[0] && false === strpos($key, 'esa_')) {
                    $columns[$key][] = $item;
                }
            }
            foreach ($source->getLanguages() as $store => $language) {
                if ($product->has($store)) {
                    foreach ($product->get($store) as $key => $item) {
                        if ('_' !== $key[0] && false === strpos($key, 'esa_')) {
                            $columns[$key][] = $item;
                        }
                    }
                }
            }
        }

        foreach ($columns as $key => $array) {
            $columns[$key] = array_unique($array);
        }

        foreach ($transformer->getAttributes() as $field => $converter) {
            $type = $transformer->getAttributeType($field);
            if (SelectAttribute::TYPE === $type || MultiSelectAttribute::TYPE === $type) {
                $attributeCode = new AttributeCode($field);
                if (!array_key_exists($field, $columns)) {
                    $columns[$field] = [];
                }
                $options = $this->getOptions($columns[$field]);
                foreach ($options as $key => $option) {
                    $record = new Record();
                    $record->set('attribute_code', $attributeCode->getValue());
                    $record->set('option_code', $key);
                    $record->setValue($source->getDefaultLanguage()->getCode(), $option);
                    $result[] = $record;
                }
            }
        }

        $i = 0;
        $count = count($result);
        foreach ($result as $option) {
            $i++;
            $records = new Progress($i, $count);
            $command = new ProcessImportCommand(
                $import->getId(),
                $steps,
                $records,
                $option,
                OptionImportAction::TYPE
            );
            $line = new ImportLine($import->getId(), $steps->getPosition(), $i);
            $this->repository->save($line);
            $this->commandBus->dispatch($command, true);
        }
    }

    /**
     * @param array $column
     *
     * @return array
     */
    private function getOptions(array $column): array
    {
        $result = [];
        $unique = array_unique($column);
        foreach ($unique as $element) {
            if ('' !== $element && null !== $element) {
                $result[$element] = new StringValue($element);
            }
        }

        return $result;
    }
}
