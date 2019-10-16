<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Provider;

use Ergonode\Condition\Domain\Condition\AttributeExistsCondition;
use Ergonode\Condition\Domain\Condition\LanguageCompletenessCondition;
use Ergonode\Condition\Domain\Condition\NumericAttributeValueCondition;
use Ergonode\Condition\Domain\Condition\OptionAttributeValueCondition;
use Ergonode\Condition\Domain\Condition\ProductCompletenessCondition;
use Ergonode\Condition\Domain\Condition\RoleExactlyCondition;
use Ergonode\Condition\Domain\Condition\TextAttributeValueCondition;
use Ergonode\Condition\Domain\Condition\UserExactlyCondition;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ConditionDictionaryProvider
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getDictionary(Language $language): array
    {
        return [
            AttributeExistsCondition::TYPE => $this->translator->trans(AttributeExistsCondition::TYPE, [], 'condition', $language->getCode()),
            TextAttributeValueCondition::TYPE => $this->translator->trans(TextAttributeValueCondition::TYPE, [], 'condition', $language->getCode()),
            OptionAttributeValueCondition::TYPE => $this->translator->trans(OptionAttributeValueCondition::TYPE, [], 'condition', $language->getCode()),
            NumericAttributeValueCondition::TYPE => $this->translator->trans(NumericAttributeValueCondition::TYPE, [], 'condition', $language->getCode()),
            LanguageCompletenessCondition::TYPE => $this->translator->trans(LanguageCompletenessCondition::TYPE, [], 'condition', $language->getCode()),
            ProductCompletenessCondition::TYPE => $this->translator->trans(ProductCompletenessCondition::TYPE, [], 'condition', $language->getCode()),
            RoleExactlyCondition::TYPE => $this->translator->trans(RoleExactlyCondition::TYPE, [], 'condition', $language->getCode()),
            UserExactlyCondition::TYPE => $this->translator->trans(UserExactlyCondition::TYPE, [], 'condition', $language->getCode()),
        ];
    }
}
