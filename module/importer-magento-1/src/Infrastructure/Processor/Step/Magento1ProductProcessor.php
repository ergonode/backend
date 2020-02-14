<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
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
     * @param Import            $import
     * @param ProductModel[]    $products
     * @param Transformer       $transformer
     * @param Magento1CsvSource $source
     */
    public function process(Import $import, array $products, Transformer $transformer, Magento1CsvSource $source): void
    {
        $i = 0;
        $products = $this->getGroupedProducts($products);
        /** @var ProductModel $product */
        foreach ($products['simple'] as $product) {
            $i++;
            $record = $this->getRecord($product, $transformer, $source);
            $command = new ProcessImportCommand($import->getId(), $i, $record, ProductImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }

        echo print_r(sprintf('SEND %s Products', $i), true) . PHP_EOL;
    }

    /**
     * @param ProductModel[] $products
     *
     * @return array
     */
    private function getGroupedProducts(array $products): array
    {
        $result['simple'] = [];
        foreach ($products as $product) {
            $type = $product->get('default')['esa_type'];
            $result[$type][] = $product;

        }

        return $result;
    }

    /**
     * @param ProductModel      $product
     * @param Transformer       $transformer
     *
     * @param Magento1CsvSource $source
     *
     * @return Record
     */
    public function getRecord(ProductModel $product, Transformer $transformer, Magento1CsvSource $source): Record
    {
        $default = $product->get('default');

        $record = new Record();

        foreach ($default as $field => $value) {
            if ($transformer->hasAttribute($field)) {
                $type = $transformer->getAttributeType($field);
                if (null === $value) {
                    $record->setValue($field, null);
                } else {
                    if ($type === SelectAttribute::TYPE || $type === MultiSelectAttribute::TYPE) {
                        $record->setValue($field, new Stringvalue($value));
                    } elseif ($type === ImageAttribute::TYPE) {
                        if($source->importMultimedia()) {
                            $multimediaId = MultimediaId::fromKey($source->getUrl() . $value);
                            $record->setValue($field, new Stringvalue($multimediaId->getValue()));
                        }
                    } else {
                        $translation[Language::EN] = $value;
                        foreach ($source->getLanguages() as $key => $language) {
                            $translatedVersion = $product->get($key);
                            if (array_key_exists($field, $translatedVersion) && $translatedVersion[$field] !== null) {
                                $translation[$language->getCode()] = $translatedVersion[$field];
                            }
                        }
                        $record->setValue($field, new TranslatableStringValue(new TranslatableString($translation)));
                    }
                }
            }

            if ($transformer->hasField($field) && $value !== null) {
                $record->set($field, new StringValue($value));
            }
        }
        return $record;
    }
}
