<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class AttributeTranslationExistsCondition implements ConditionInterface
{
    public const TYPE = 'ATTRIBUTE_TRANSLATION_EXISTS_CONDITION';
    public const PHRASE = 'ATTRIBUTE_TRANSLATION_EXISTS_CONDITION_PHRASE';

    private AttributeId $attribute;

    private Language $language;

    public function __construct(AttributeId $attribute, Language $language)
    {
        $this->attribute = $attribute;
        $this->language = $language;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getAttribute(): AttributeId
    {
        return $this->attribute;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }
}
