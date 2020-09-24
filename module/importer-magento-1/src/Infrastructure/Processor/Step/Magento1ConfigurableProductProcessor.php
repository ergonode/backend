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
use Ergonode\Importer\Infrastructure\Action\VariableProductImportAction;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Product\Domain\Entity\VariableProduct;

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
     * @param OptionQueryInterface    $optionQuery
     * @param AttributeQueryInterface $attributeQuery
     * @param ProductQueryInterface   $productQuery
     * @param CommandBusInterface     $commandBus
     */
    public function __construct(
        OptionQueryInterface $optionQuery,
        AttributeQueryInterface $attributeQuery,
        ProductQueryInterface $productQuery,
        CommandBusInterface $commandBus
    ) {
        parent::__construct($optionQuery);
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
            $record = $this->getRecord($product, $transformer, $source);
            $command = new ProcessImportCommand(
                $import->getId(),
                $record,
                VariableProductImportAction::TYPE
            );
            $this->commandBus->dispatch($command, true);
        }
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
        $record->set('sku', $product->getSku());
        $record->set('esa_template', $product->getTemplate());

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

            $bindings = $this->getBindings($product, $default);

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
     * @param ProductModel $productModel
     * @param array        $default
     *
     * @return null|string
     */
    private function getBindings(ProductModel $productModel, array $default): ?string
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
