<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Webmozart\Assert\Assert;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ramsey\Uuid\Uuid;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;

/**
 */
abstract class AbstractProductProcessor
{
    private const NAMESPACE = 'e1f84ee9-14f2-4e52-981a-b6b82006ada8';

    /**
     * @var OptionQueryInterface
     */
    private OptionQueryInterface $optionQuery;

    /**
     * @param OptionQueryInterface $optionQuery
     */
    public function __construct(OptionQueryInterface $optionQuery)
    {
        $this->optionQuery = $optionQuery;
    }

    /**
     * @param ProductModel[] $products
     *
     * @param string         $type
     *
     * @return array
     */
    protected function getProducts(array $products, string $type): array
    {
        $result = [];
        foreach ($products as $product) {
            if ($type === $product->get('default')['esa_type']) {
                $result[] = $product;
            }
        }

        return $result;
    }

    /**
     * @param AttributeId       $attributeId
     * @param string            $field
     * @param string            $value
     * @param Record            $record
     * @param ProductModel      $product
     * @param Magento1CsvSource $source
     */
    protected function buildSelect(
        AttributeId $attributeId,
        string $field,
        string $value,
        Record $record,
        ProductModel $product,
        Magento1CsvSource $source
    ): void {
        $optionKey = new OptionKey($value);
        $optionId = $this->optionQuery->findIdByAttributeIdAndCode($attributeId, $optionKey);

        Assert::notNull(
            $optionId,
            sprintf(
                'Can\'t find optionId for %s option in %s attribute',
                $optionKey->getValue(),
                $attributeId->getValue()
            )
        );

        $translation[$source->getDefaultLanguage()->getCode()] = $optionId->getValue();
        foreach ($source->getLanguages() as $key => $language) {
            if ($product->has($key)) {
                $translatedVer = $product->get($key);
                if (array_key_exists($field, $translatedVer) && null !== $translatedVer[$field]) {
                    $optionKey = new OptionKey($translatedVer[$field]);
                    $optionId = $this->optionQuery->findIdByAttributeIdAndCode(
                        $attributeId,
                        $optionKey
                    );
                    Assert::notNull(
                        $optionId,
                        sprintf(
                            'Can\'t find optionId for %s option in %s attribute',
                            $optionKey->getValue(),
                            $attributeId->getValue()
                        )
                    );

                    $translation[$source->getDefaultLanguage()->getCode()] = $optionId->getValue();
                    $translation[$language->getCode()] = $translatedVer[$field];
                }
            }
        }

        $record->setValue($field, new TranslatableStringValue(new TranslatableString($translation)));
    }

    /**
     * @param AttributeId       $attributeId
     * @param string            $field
     * @param string            $value
     * @param Record            $record
     * @param ProductModel      $product
     * @param Magento1CsvSource $source
     */
    protected function buildMultiSelect(
        AttributeId $attributeId,
        string $field,
        string $value,
        Record $record,
        ProductModel $product,
        Magento1CsvSource $source
    ): void {
        $optionKey = new OptionKey($value);
        $optionId = $this->optionQuery->findIdByAttributeIdAndCode($attributeId, $optionKey);
        Assert::notNull(
            $optionId,
            sprintf(
                'Can\'t find optionId for %s option in %s attribute',
                $optionKey->getValue(),
                $attributeId->getValue(),
            )
        );
        $translation[$source->getDefaultLanguage()->getCode()] = $optionId->getValue();
        foreach ($source->getLanguages() as $key => $language) {
            if ($product->has($key)) {
                $translatedVer = $product->get($key);
                if (array_key_exists($field, $translatedVer) && null !== $translatedVer[$field]) {
                    $optionKey = new OptionKey($translatedVer[$field]);
                    $optionId = $this->optionQuery->findIdByAttributeIdAndCode(
                        $attributeId,
                        $optionKey
                    );
                    $translation[$language->getCode()] = $optionId->getValue();
                }
            }
        }

        $record->setValue($field, new StringCollectionValue($translation));
    }

    /**
     * @param string            $field
     * @param string            $value
     * @param Record            $record
     * @param Magento1CsvSource $source
     */
    protected function buildImage(
        string $field,
        string $value,
        Record $record,
        Magento1CsvSource $source
    ): void {
        if ($source->import(Magento1CsvSource::MULTIMEDIA)) {
            $url = sprintf('%s/media/catalog/product%s', $source->getHost(), $value);
            if (strpos($url, 'no_selection') === false) {
                $uuid = Uuid::uuid5(self::NAMESPACE, $url)->toString();
                $multimediaId = new MultimediaId($uuid);
                $record->setValue($field, new Stringvalue($multimediaId->getValue()));
            }
        }
    }
}
