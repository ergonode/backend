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
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ramsey\Uuid\Uuid;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Importer\Infrastructure\Action\VariableProductImportAction;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;

/**
 */
class Magento1ConfigurableProductProcessor implements Magento1ProcessorStepInterface
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
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;

    /**
     * @var ImportLineRepositoryInterface
     */
    private ImportLineRepositoryInterface $repository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param OptionQueryInterface          $optionQuery
     * @param AttributeQueryInterface       $attributeQuery
     * @param ProductQueryInterface         $productQuery
     * @param ImportLineRepositoryInterface $repository
     * @param CommandBusInterface           $commandBus
     */
    public function __construct(
        OptionQueryInterface $optionQuery,
        AttributeQueryInterface $attributeQuery,
        ProductQueryInterface $productQuery,
        ImportLineRepositoryInterface $repository,
        CommandBusInterface $commandBus
    ) {
        $this->optionQuery = $optionQuery;
        $this->attributeQuery = $attributeQuery;
        $this->productQuery = $productQuery;
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
        $products = $this->getProducts($products);
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
                VariableProductImportAction::TYPE
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
    private function getProducts(array $products): array
    {
        $result = [];
        foreach ($products as $product) {
            $type = $product->get('default')['esa_type'];
            if ('configurable' === $type) {
                $result[] = $product;
            }
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
            $translation = [];
            if ($transformer->hasAttribute($field)) {
                $type = $transformer->getAttributeType($field);
                $isMultilingual = $transformer->isAttributeMultilingual($field);
                $attributeId = $this->attributeQuery->findAttributeByCode(new AttributeCode($field));
                if (null === $value) {
                    $record->setValue($field, null);
                } else {
                    if (SelectAttribute::TYPE === $type) {
                        $optionKey = new OptionKey($value);
                        $optionId = $this->optionQuery->findIdByAttributeIdAndCode($attributeId->getId(), $optionKey);
                        $translation[$source->getDefaultLanguage()->getCode()] = $optionId->getValue();
                        foreach ($source->getLanguages() as $key => $language) {
                            if ($product->has($key)) {
                                $translatedVer = $product->get($key);
                                if (array_key_exists($field, $translatedVer) && null !== $translatedVer[$field]) {
                                    $optionKey = new OptionKey($translatedVer[$field]);
                                    $optionId = $this->optionQuery->findIdByAttributeIdAndCode(
                                        $attributeId->getId(),
                                        $optionKey
                                    );
                                    $translation[$source->getDefaultLanguage()->getCode()] = $optionId->getValue();
                                    $translation[$language->getCode()] = $translatedVer[$field];
                                }
                            }
                        }

                        $record->setValue($field, new TranslatableStringValue(new TranslatableString($translation)));
                    } elseif (MultiSelectAttribute::TYPE === $type) {
                        $optionKey = new OptionKey($value);
                        $optionId = $this->optionQuery->findIdByAttributeIdAndCode($attributeId->getId(), $optionKey);
                        $translation[$source->getDefaultLanguage()->getCode()] = $optionId->getValue();
                        foreach ($source->getLanguages() as $key => $language) {
                            if ($product->has($key)) {
                                $translatedVer = $product->get($key);
                                if (array_key_exists($field, $translatedVer) && null !== $translatedVer[$field]) {
                                    $optionKey = new OptionKey($translatedVer[$field]);
                                    $optionId = $this->optionQuery->findIdByAttributeIdAndCode(
                                        $attributeId->getId(),
                                        $optionKey
                                    );
                                    $translation[$language->getCode()] = $optionId->getValue();
                                }
                            }
                        }

                        $record->setValue($field, new StringCollectionValue($translation));
                    } elseif (ImageAttribute::TYPE === $type) {
                        if ($source->import(Magento1CsvSource::MULTIMEDIA)) {
                            $url = sprintf('%s/media/catalog/product%s', $source->getHost(), $value);
                            if (strpos($url, 'no_selection') === false) {
                                $uuid = Uuid::uuid5(self::NAMESPACE, $url)->toString();
                                $multimediaId = new MultimediaId($uuid);
                                $record->setValue($field, new Stringvalue($multimediaId->getValue()));
                            }
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

            $bindings = $this->getBindings($default);

            if ($bindings) {
                $record->set('bindings', $bindings);
            }

            $variants = $this->getVariants($default);

            if ($variants) {
                $record->set('variants', $variants);
            }

            if (null !== $value
                && '' !== $value
                && 'variants' !== $field
                && 'bindings' !== $field
                && $transformer->hasField($field)
                && !$record->has($field)
            ) {
                $record->set($field, $value);
            }
        }

        return $record;
    }

    /**
     * @param array $default
     *
     * @return null|string
     */
    private function getBindings(array $default): ?string
    {
        $result = [];

        if (array_key_exists('bindings', $default)) {
            $bindings = explode(',', $default['bindings']);
            $bindings = array_unique($bindings);

            foreach ($bindings as $binding) {
                $code = new AttributeCode($binding);
                $model = $this->attributeQuery->findAttributeByCode($code);
                Assert::notNull($model, sprintf('Can\'t find attribute %s for binding', $code));
                $result[] = $model->getId()->getValue();
            }
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

        if (array_key_exists('variants', $default)) {
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
