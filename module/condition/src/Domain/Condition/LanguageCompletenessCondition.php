<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Webmozart\Assert\Assert;

class LanguageCompletenessCondition implements ConditionInterface
{
    public const TYPE = 'LANGUAGE_COMPLETENESS_CONDITION';
    public const PHRASE = 'LANGUAGE_COMPLETENESS_CONDITION_PHRASE';

    public const COMPLETE = 'complete';
    public const NOT_COMPLETE = 'not complete';

    public const PRODUCT_TRANSLATION_COMPLETE = 'PRODUCT_TRANSLATION_COMPLETE';
    public const PRODUCT_TRANSLATION_NOT_COMPLETE = 'PRODUCT_TRANSLATION_NOT_COMPLETE';

    private string $completeness;

    private Language $language;

    public function __construct(string $completeness, Language $language)
    {
        Assert::oneOf($completeness, [self::COMPLETE, self::NOT_COMPLETE]);

        $this->completeness = $completeness;
        $this->language = $language;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getCompleteness(): string
    {
        return $this->completeness;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }
}
