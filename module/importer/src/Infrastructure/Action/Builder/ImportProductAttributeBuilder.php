<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action\Builder;

use Ergonode\Transformer\Domain\Model\ImportedProduct;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Webmozart\Assert\Assert;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;

/**
 */
class ImportProductAttributeBuilder implements ProductImportBuilderInterface
{
    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $attributeQuery;

    /**
     * @var OptionQueryInterface
     */
    private OptionQueryInterface $optionQuery;

    /**
     * @var MultimediaQueryInterface
     */
    private MultimediaQueryInterface $multimediaQuery;

    /**
     * @param AttributeQueryInterface  $attributeQuery
     * @param OptionQueryInterface     $optionQuery
     * @param MultimediaQueryInterface $multimediaQuery
     */
    public function __construct(
        AttributeQueryInterface $attributeQuery,
        OptionQueryInterface $optionQuery,
        MultimediaQueryInterface $multimediaQuery
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->optionQuery = $optionQuery;
        $this->multimediaQuery = $multimediaQuery;
    }

    /**
     * @param ImportedProduct $product
     * @param Record          $record
     *
     * @return ImportedProduct
     *
     * @throws \Exception
     */
    public function build(ImportedProduct $product, Record $record): ImportedProduct
    {
        foreach ($record->getAttributes() as $code => $value) {
            $code = new AttributeCode($code);
            $id = $this->attributeQuery->findAttributeIdByCode($code);
            Assert::notNull($id, sprintf('Attribute %s not exists', $code));
            $type = $this->attributeQuery->findAttributeType($id);
            Assert::notNull($id, sprintf('Attribute type %s not exists', $code));

            $product->attributes[$code->getValue()] = null;
            if (SelectAttribute::TYPE === $type->getValue()) {
                $product->attributes[$code->getValue()] = $this->buildSelect($id, $code, $value);
            } elseif (MultiSelectAttribute::TYPE === $type->getValue()) {
                $product->attributes[$code->getValue()] = $this->buildMultiSelect($id, $code, $value);
            } elseif (ImageAttribute::TYPE === $type->getValue()) {
                $product->attributes[$code->getValue()] = $this->buildImage($id, $code, $value);
            } else {
                $result = [];
                foreach ($value as $key => $version) {
                    if ('' !== $version && null !== $version) {
                        $result[$key] = $version;
                    }
                }
                $product->attributes[$code->getValue()] = new TranslatableStringValue(new TranslatableString($result));
            }
        }

        return $product;
    }

    /**
     * @param AttributeId   $id
     * @param AttributeCode $code
     * @param array         $value
     *
     * @return ValueInterface
     */
    protected function buildSelect(
        AttributeId $id,
        AttributeCode $code,
        array $value
    ): ValueInterface {
        $result = [];
        foreach ($value as $language => $version) {
            $key = new OptionKey($version);
            $optionId = $this->optionQuery->findIdByAttributeIdAndCode($id, $key);

            Assert::notNull(
                $optionId,
                sprintf('Can\'t find id for %s option in %s attribute', $key->getValue(), $code->getValue())
            );

            $result[$language] = $optionId;
        }

        return new TranslatableStringValue(new TranslatableString($result));
    }

    /**
     * @param AttributeId   $id
     * @param AttributeCode $code
     * @param array         $value
     *
     * @return ValueInterface
     */
    protected function buildMultiSelect(
        AttributeId $id,
        AttributeCode $code,
        array $value
    ): ValueInterface {
        $result = [];
        foreach ($value as $language => $version) {
            $options = [];
            foreach (explode(',', $version) as $item) {
                $key = new OptionKey($item);
                $optionId = $this->optionQuery->findIdByAttributeIdAndCode($id, $key);

                Assert::notNull(
                    $optionId,
                    sprintf('Can\'t find id for %s option in %s attribute', $key->getValue(), $code->getValue())
                );
                $options[] = $optionId;
            }
            $result[$language] = implode(',', $options);
        }

        return new TranslatableStringValue(new TranslatableString($result));
    }

    /**
     * @param AttributeId   $id
     * @param AttributeCode $code
     * @param array         $value
     *
     * @return ValueInterface
     */
    protected function buildImage(
        AttributeId $id,
        AttributeCode $code,
        array $value
    ): ValueInterface {
        $result = [];
        foreach ($value as $language => $version) {
            if ($version) {
                $multimediaId = $this->multimediaQuery->findIdByFilename($version);
                Assert::notNull($multimediaId, sprintf('Can\'t find multimedia %s file', $version));
                $result[$language] = $multimediaId->getValue();
            }
        }

        return new TranslatableStringValue(new TranslatableString($result));
    }
}
