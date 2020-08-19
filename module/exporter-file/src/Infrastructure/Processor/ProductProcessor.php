<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Exporter\Infrastructure\Exception\ExportException;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\DataStructure\LanguageData;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

/**
 */
class ProductProcessor
{
    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $attributeQuery;

    /**
     * @var TranslationInheritanceCalculator
     */
    private TranslationInheritanceCalculator $calculator;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @param AttributeQueryInterface          $attributeQuery
     * @param TranslationInheritanceCalculator $calculator
     * @param AttributeRepositoryInterface     $attributeRepository
     */
    public function __construct(
        AttributeQueryInterface $attributeQuery,
        TranslationInheritanceCalculator $calculator,
        AttributeRepositoryInterface $attributeRepository
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->calculator = $calculator;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param FileExportChannel $channel
     * @param AbstractProduct   $product
     *
     * @return ExportData
     *
     * @throws ExportException
     */
    public function process(FileExportChannel $channel, AbstractProduct $product): ExportData
    {
        try {
            $data = new ExportData();

            $attributes = $this->attributeQuery->getDictionary();
            asort($attributes);

            foreach ($channel->getLanguages() as $language) {
                $data->set($this->getLanguage($product, $language, $attributes), $language);
            }

            return $data;
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $product->getSku()->getValue()),
                $exception
            );
        }
    }

    /**
     * @param AbstractProduct $product
     * @param Language        $language
     * @param array           $attributes
     *
     * @return LanguageData
     */
    private function getLanguage(AbstractProduct $product, Language $language, array $attributes): LanguageData
    {
        $result = new LanguageData();
        $result->set('_id', $product->getId()->getValue());
        $result->set('_sku', $product->getSku()->getValue());
        $result->set('_type', $product->getType());
        $result->set('_template', $product->getTemplateId()->getValue());
        foreach ($attributes as $attributeId => $code) {
            $code = new AttributeCode($code);
            if ($product->hasAttribute($code)) {
                $attribute = $this->attributeRepository->load(new AttributeId($attributeId));
                Assert::isInstanceOf($attribute, AbstractAttribute::class);
                $value = $product->getAttribute($code);
                $calculatedValue = $this->calculator->calculate($attribute, $value, $language);
                if (is_array($calculatedValue)) {
                    $calculatedValue = implode(',', $calculatedValue);
                }

                $result->set($code->getValue(), $calculatedValue);
            } else {
                $result->set($code->getValue(), null);
            }
        }

        return $result;
    }
}
