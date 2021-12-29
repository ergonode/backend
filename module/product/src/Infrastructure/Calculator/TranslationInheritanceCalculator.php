<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Calculator;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;

class TranslationInheritanceCalculator
{
    private LanguageQueryInterface $languageQuery;

    public function __construct(LanguageQueryInterface $languageQuery)
    {
        $this->languageQuery = $languageQuery;
    }

    /**
     * @return string|array|null
     */
    public function calculate(AttributeScope $scope, ValueInterface $value, Language $language)
    {
        $calculatedValue = null;
        if ($value instanceof TranslatableStringValue || $value instanceof StringCollectionValue) {
            $translations = $value->getValue();
            $find = false;
            $setUp = false;
            if ($scope->isLocal()) {
                $languagesPath = $this->languageQuery->getInheritancePath($language);
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
            } else {
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
