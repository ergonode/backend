<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\ValueObject\Progress;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Importer\Infrastructure\Action\GroupedProductImportAction;
use Webmozart\Assert\Assert;

/**
 */
class Magento1BundleProductProcessor extends AbstractProductProcessor implements Magento1ProcessorStepInterface
{
    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $attributeQuery;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param OptionQueryInterface    $optionQuery
     * @param AttributeQueryInterface $attributeQuery
     * @param CommandBusInterface     $commandBus
     */
    public function __construct(
        OptionQueryInterface $optionQuery,
        AttributeQueryInterface $attributeQuery,
        CommandBusInterface $commandBus
    ) {
        parent::__construct($optionQuery);
        $this->attributeQuery = $attributeQuery;
        $this->commandBus = $commandBus;
    }

    /**
     * @param Import            $import
     * @param array             $products
     * @param Transformer       $transformer
     * @param Magento1CsvSource $source
     * @param Progress          $steps
     *
     * @return int
     */
    public function process(
        Import $import,
        array $products,
        Transformer $transformer,
        Magento1CsvSource $source,
        Progress $steps
    ): int {
        $i = 0;
        $products = $this->getProducts($products, 'bundle');
        $count = count($products);
        /** @var ProductModel $product */
        foreach ($products as $product) {
            $record = $this->getRecord($product, $transformer, $source);
            $i++;
            $records = new Progress($i, $count);
            $command = new ProcessImportCommand(
                $import->getId(),
                $steps,
                $records,
                $record,
                GroupedProductImportAction::TYPE
            );
            $this->commandBus->dispatch($command, true);
        }

        return $count;
    }

    /**
     * @param ProductModel      $product
     * @param Transformer       $transformer
     *
     * @param Magento1CsvSource $source
     *
     * @return Record
     */
    private function getRecord(ProductModel $product, Transformer $transformer, Magento1CsvSource $source): Record
    {
        $default = $product->get('default');

        $record = new Record();

        foreach ($default as $field => $value) {
            $translation = [];
            if ($transformer->hasAttribute($field)) {
                $type = $transformer->getAttributeType($field);
                $isMultilingual = $transformer->isAttributeMultilingual($field);
                $attribute = $this->attributeQuery->findAttributeByCode(new AttributeCode($field));
                Assert::notNull($attribute);
                if (null === $value) {
                    $record->setValue($field, null);
                } else {
                    if (SelectAttribute::TYPE === $type) {
                        $this->buildSelect($attribute->getId(), $field, $value, $record, $product, $source);
                    } elseif (MultiSelectAttribute::TYPE === $type) {
                        $this->buildMultiSelect($attribute->getId(), $field, $value, $record, $product, $source);
                    } elseif (ImageAttribute::TYPE === $type) {
                        $this->buildImage($field, $value, $record, $source);
                    } elseif ($isMultilingual) {
                        $translation[$source->getDefaultLanguage()->getCode()] = $value;
                        foreach ($source->getLanguages() as $key => $language) {
                            if ($product->has($key)) {
                                $translatedVer = $product->get($key);
                                if (array_key_exists($field, $translatedVer) && null !== $translatedVer[$field]) {
                                    $translation[$language->getCode()] = $translatedVer[$field];
                                }
                            }
                        }
                        $record->setValue($field, new TranslatableStringValue(new TranslatableString($translation)));
                    } elseif (null !== $value) {
                        $record->setValue($field, new StringValue($value));
                    }
                }
            }

            if (null !== $value
                && '' !== $value
                && $transformer->hasField($field)
                && !$record->has($field)
            ) {
                $record->set($field, $value);
            }
        }

        return $record;
    }
}
