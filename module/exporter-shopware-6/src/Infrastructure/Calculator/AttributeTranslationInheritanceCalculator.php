<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Calculator;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
class AttributeTranslationInheritanceCalculator
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
     * @return array|mixed|string
     */
    public function calculate(AbstractAttribute $attribute, ValueInterface $value, Language $language)
    {
        if ($value instanceof TranslatableStringValue || $value instanceof StringCollectionValue) {
            $translations = $value->getValue();
            if ($attribute->getScope()->isGlobal()) {
                $inheritance = $this->languageQuery->getRootLanguage();

                return $translations[$inheritance->getCode()];
            }

            return $translations[$language->getCode()];
        }
        if ($value instanceof StringValue) {
            $array = $value->getValue();

            return reset($array);
        }

        return $value->getValue();
    }
}
