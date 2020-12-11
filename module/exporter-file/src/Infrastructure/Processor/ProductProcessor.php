<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Exporter\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;
use Ergonode\ExporterFile\Infrastructure\DataStructure\LanguageData;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

class ProductProcessor
{
    private AttributeQueryInterface $attributeQuery;

    private TranslationInheritanceCalculator $calculator;

    private AttributeRepositoryInterface $attributeRepository;

    private TemplateRepositoryInterface $templateRepository;

    private OptionQueryInterface $optionQuery;

    public function __construct(
        AttributeQueryInterface $attributeQuery,
        TranslationInheritanceCalculator $calculator,
        AttributeRepositoryInterface $attributeRepository,
        TemplateRepositoryInterface $templateRepository,
        OptionQueryInterface $optionQuery
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->calculator = $calculator;
        $this->attributeRepository = $attributeRepository;
        $this->templateRepository = $templateRepository;
        $this->optionQuery = $optionQuery;
    }

    /**
     * @throws ExportException
     */
    public function process(FileExportChannel $channel, AbstractProduct $product): ExportData
    {
        try {
            $attributes = $this->attributeQuery->getDictionary();
            asort($attributes);

            $template = $this->templateRepository->load($product->getTemplateId());
            if (null === $template) {
                throw new \InvalidArgumentException('Template not found');
            }

            $data = new ExportData();
            foreach ($channel->getLanguages() as $language) {
                $data->set($this->getLanguage($product, $language, $attributes, $template), $language);
            }

            return $data;
        } catch (\Exception $exception) {
            throw new ExportException(
                sprintf('Can\'t process export for %s', $product->getSku()->getValue()),
                $exception
            );
        }
    }

    private function getLanguage(
        AbstractProduct $product,
        Language $language,
        array $attributes,
        Template $template
    ): LanguageData {
        $result = new LanguageData();
        $result->set('_id', $product->getId()->getValue());
        $result->set('_sku', $product->getSku()->getValue());
        $result->set('_type', $product->getType());
        $result->set('_language', $language->getCode());
        $result->set('_template', $template->getName());
        foreach ($attributes as $attributeId => $code) {
            $code = new AttributeCode($code);
            if ($product->hasAttribute($code)) {
                $attribute = $this->attributeRepository->load(new AttributeId($attributeId));
                Assert::isInstanceOf($attribute, AbstractAttribute::class);
                $value = $product->getAttribute($code);
                $calculatedValue = $this->calculator->calculate($attribute, $value, $language);
                if ($attribute instanceof AbstractOptionAttribute && $calculatedValue) {
                    $calculatedValue = $this->resolveOptionKey($calculatedValue, $attribute->getCode());
                }
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

    /**
     * @param string|string[]$value
     *
     * @return string|string[]
     */
    private function resolveOptionKey($value, AttributeCode $code)
    {
        if (is_string($value)) {
            return $this->findKey($value, $code)->getValue();
        }

        return array_map(
            fn (string $id) => $this->findKey($id, $code)->getValue(),
            $value,
        );
    }

    private function findKey(string $value, AttributeCode $code): OptionKey
    {
        $key = $this->optionQuery->findKey(new AggregateId($value));
        if (!$key) {
            throw new \RuntimeException("There's no option [$value] for '{$code->getValue()}' attribute.");
        }

        return $key;
    }
}
