<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Entity\ImportLine;
use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Ergonode\Importer\Domain\ValueObject\Progress;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1ProcessorStepInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Transformer\Infrastructure\Action\ProductImportAction;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ramsey\Uuid\Uuid;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;

/**
 */
class Magento1ProductProcessor implements Magento1ProcessorStepInterface
{
    private const NAMESPACE = 'e1f84ee9-14f2-4e52-981a-b6b82006ada8';

    /**
     * @var OptionQueryInterface
     */
    private OptionQueryInterface $optionQuery;

    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $attributeQuery;

    /**
     * @var ImportLineRepositoryInterface
     */
    private ImportLineRepositoryInterface $repository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * Magento1ProductProcessor constructor.
     *
     * @param OptionQueryInterface          $optionQuery
     * @param AttributeQueryInterface       $attributeQuery
     * @param ImportLineRepositoryInterface $repository
     * @param CommandBusInterface           $commandBus
     */
    public function __construct(
        OptionQueryInterface $optionQuery,
        AttributeQueryInterface $attributeQuery,
        ImportLineRepositoryInterface $repository,
        CommandBusInterface $commandBus
    ) {
        $this->optionQuery = $optionQuery;
        $this->attributeQuery = $attributeQuery;
        $this->repository = $repository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param Import            $import
     * @param array             $products
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
        $i = 0;
        $products = $this->getGroupedProducts($products);
        $count = count($products['simple']);
        /** @var ProductModel $product */
        foreach ($products['simple'] as $product) {
            $record = $this->getRecord($product, $transformer, $source);
            $i++;
            $records = new Progress($i, $count);
            $command = new ProcessImportCommand(
                $import->getId(),
                $steps,
                $records,
                $record,
                ProductImportAction::TYPE
            );
            $line = new ImportLine($import->getId(), $steps->getPosition(), $i);
            $this->repository->save($line);
            $this->commandBus->dispatch($command, true);
        }
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
    private function getRecord(ProductModel $product, Transformer $transformer, Magento1CsvSource $source): Record
    {
        $default = $product->get('default');

        $record = new Record();

        foreach ($default as $field => $value) {
            if ($transformer->hasAttribute($field)) {
                $type = $transformer->getAttributeType($field);
                $isMultilingual = $transformer->isAttributeMultilingual($field);
                $attributeId = $this->attributeQuery->findAttributeByCode(new AttributeCode($field));
                if (null === $value) {
                    $record->setValue($field, null);
                } else {
                    if (SelectAttribute::TYPE === $type || MultiSelectAttribute::TYPE === $type) {
                        $optionKey = new OptionKey($value);
                        $optionId = $this->optionQuery->findIdByAttributeIdAndCode($attributeId->getId(), $optionKey);
                        $record->setValue($field, new Stringvalue($optionId->getValue()));
                    } elseif (ImageAttribute::TYPE === $type) {
                        if ($source->import(Magento1CsvSource::MULTIMEDIA)) {
                            $url = $source->getHost().$value;
                            $uuid  = Uuid::uuid5(self::NAMESPACE, $url)->toString();
                            $multimediaId = new MultimediaId($uuid);
                            $record->setValue($field, new Stringvalue($multimediaId->getValue()));
                        }
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

            if ($transformer->hasField($field) && (null !== $value)) {
                $record->set($field, new StringValue($value));
            }
        }

        return $record;
    }
}
