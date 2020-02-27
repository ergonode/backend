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
use Ergonode\Attribute\Application\Form\Model\AttributeOptionModel;
use Ergonode\Transformer\Infrastructure\Action\AttributeImportAction;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Ergonode\Importer\Domain\Entity\ImportLine;
use Doctrine\DBAL\DBALException;

/**
 */
class Magento1AttributeProcessor implements Magento1ProcessorStepInterface
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
        }

        foreach ($transformer->getAttributes() as $field => $converter) {
            $attributeCode = new AttributeCode($field);
            $type = $transformer->getAttributeType($field);
            $record = new Record();
            $record->set('code', new StringValue($attributeCode->getValue()));
            $record->set('type', new StringValue($type));
            $multilingual = $transformer->isAttributeMultilingual($field) ? '1' : '0';
            $record->set('multilingual', new StringValue($multilingual));
            $record->set(
                'label',
                new TranslatableStringValue(
                    new TranslatableString([$source->getDefaultLanguage()->getCode() => $field])
                )
            );
            if (SelectAttribute::TYPE === $type || MultiSelectAttribute::TYPE === $type) {
                $options = $this->getOptions($columns[$field]);
                foreach ($options as $key => $option) {
                    $record->setValue($key, $option);
                }
            }
            $result[] = $record;
        }

        $i = 0;
        $count = count($result);
        foreach ($result as $attribute) {
            $i++;
            $records = new Progress($i, $count);
            $command = new ProcessImportCommand(
                $import->getId(),
                $steps,
                $records,
                $attribute,
                AttributeImportAction::TYPE
            );
            $line = new ImportLine($import->getId(), $steps->getPosition(), $i);
            $this->repository->save($line);
            $this->commandBus->dispatch($command);
        }
    }

    /**
     * @param array $column
     *
     * @return AttributeOptionModel[]
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
