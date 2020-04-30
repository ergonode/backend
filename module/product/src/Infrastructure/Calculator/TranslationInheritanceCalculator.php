<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Calculator;

use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\StringValue;

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
     * @param ValueInterface $value
     * @param Language       $language
     *
     * @return string|null
     */
    public function calculate(ValueInterface $value, Language $language): ?string
    {
        $languagesPath = $this->languageQuery->getInheritancePath($language);
        $calculatedValue = null;
        if ($value instanceof TranslatableStringValue) {
            $translations = $value->getValue();
            $find = false;
            foreach ($languagesPath as $inheritance) {
                if ($inheritance->isEqual($language)) {
                    $find = true;
                }
                if ($find && null === $calculatedValue && array_key_exists($inheritance->getCode(), $translations)) {
                    $calculatedValue = $translations[$inheritance->getCode()];
                }
            }
        } elseif ($value instanceof StringValue) {
            $values = $value->getValue();
            $calculatedValue = reset($values);
        }

        return $calculatedValue;
    }
}
