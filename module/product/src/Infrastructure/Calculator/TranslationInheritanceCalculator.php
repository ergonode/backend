<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Calculator;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
class TranslationInheritanceCalculator
{
    /**
     * @var LanguageQueryInterface
     */
    private LanguageQueryInterface $languageQuery;

    /**
     * @param LanguageQueryInterface $languageQuery
     */
    public function __construct(LanguageQueryInterface $languageQuery)
    {
        $this->languageQuery = $languageQuery;
    }

    /**
     * @param AbstractAttribute $attribute
     * @param ValueInterface    $value
     * @param Language          $language
     *
     * @return string|array|null
     */
    public function calculate(AbstractAttribute $attribute, ValueInterface $value, Language $language)
    {
        $languagesPath = $this->languageQuery->getInheritancePath($language);
        $calculatedValue = null;
        if ($value instanceof TranslatableStringValue || $value instanceof StringCollectionValue) {
            $translations = $value->getValue();
            $find = false;
            $setUp = false;
            if ($attribute->getScope()->isLocal()) {
                foreach ($languagesPath as $inheritance) {
                    if ($inheritance->isEqual($language)) {
                        $find = true;
                    }
                    if ($find
                        && false === $setUp
                        && null === $calculatedValue
                        && array_key_exists($inheritance->getCode(), $translations)) {
                        $calculatedValue = $translations[$inheritance->getCode()];
                        $setUp = true;
                    }
                }
            }
            if ($attribute->getScope()->isGlobal()) {
                $inheritance = $this->languageQuery->getRootLanguage();
                if (array_key_exists($inheritance->getCode(), $translations)) {
                    $calculatedValue = $translations[$inheritance->getCode()];
                }
            }
        } elseif ($value instanceof StringValue) {
            $values = $value->getValue();
            $calculatedValue = reset($values);
        }

        if ($value instanceof StringCollectionValue) {
            if ('' !== $calculatedValue && null !== $calculatedValue) {
                $calculatedValue = explode(',', $calculatedValue);
            } else {
                $calculatedValue = [];
            }
        }

        return $calculatedValue;
    }
}
