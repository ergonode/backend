<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Writer;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Webmozart\Assert\Assert;

/**
 */
class XmlWriter implements WriterInterface
{
    public const TYPE = 'xml';

    /**
     * @var TranslationInheritanceCalculator
     */
    private TranslationInheritanceCalculator $calculator;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @param TranslationInheritanceCalculator $calculator
     * @param AttributeRepositoryInterface     $repository
     */
    public function __construct(TranslationInheritanceCalculator $calculator, AttributeRepositoryInterface $repository)
    {
        $this->calculator = $calculator;
        $this->repository = $repository;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool
    {
        return self::TYPE === $type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param array $headers
     *
     * @return string[]
     */
    public function start(array $headers): array
    {
        return [
            $this->add('<?xml version="1.0" encoding="UTF-8"?>'),
            $this->add('<products>', 2),
        ];
    }

    /**
     * @param AbstractProduct $product
     * @param Language[]      $languages
     * @param array           $attributes
     *
     * @return array
     */
    public function write(AbstractProduct $product, array $languages, array $attributes): array
    {
        $result = [];
        $result[] = $this->add(sprintf('<product sku="%s">', $product->getSku()->getValue()), 4);
        foreach ($languages as $language) {
            $result[] = $this->add(sprintf('<language code="%s">', $language->getCode()), 6);
            foreach ($attributes as $code) {
                $attributeCode = new AttributeCode($code);
                if ($product->hasAttribute($attributeCode)) {
                    $value = $this->getValue($product, $attributeCode, $language);
                    $result[] = $this->add(sprintf('<attribute code="%s" value="%s" />', $code, $value), 8);
                }
            }
            $result[] = $this->add('</language>', 6);
        }
        $result[] = $this->add('</product>', 4);

        return $result;
    }

    /**
     * @param array $attributes
     *
     * @return string[]
     */
    public function end(array $attributes): array
    {
        return [
            $this->add('</products>', 2),
        ];
    }

    /**
     * @param string $line
     * @param int    $ident
     *
     * @return string
     */
    private function add(string $line, int $ident = 0): string
    {
        return sprintf('%s%s%s', str_repeat(' ', $ident), $line, PHP_EOL);
    }

    /**
     * @param AbstractProduct $product
     * @param AttributeCode   $code
     * @param Language        $language
     *
     * @return string|null
     */
    private function getValue(AbstractProduct $product, AttributeCode $code, Language $language): ?string
    {
        if ($product->hasAttribute($code)) {
            $attributeId = AttributeId::fromKey($code->getValue());
            $attribute = $this->repository->load($attributeId);
            Assert::notNull($attribute);
            $value = $product->getAttribute($code);

            $result = $this->calculator->calculate($attribute, $value, $language);
            if (is_array($result)) {
                $result = implode(',', $result);
            }

            return $result;
        }

        return null;
    }
}
