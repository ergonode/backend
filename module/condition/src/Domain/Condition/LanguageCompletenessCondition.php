<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class LanguageCompletenessCondition implements ConditionInterface
{
    public const TYPE = 'LANGUAGE_COMPLETENESS_CONDITION';
    public const PHRASE = 'LANGUAGE_COMPLETENESS_CONDITION_PHRASE';

    public const COMPLETE = 'complete';
    public const NOT_COMPLETE = 'not complete';

    public const PRODUCT_TRANSLATION_COMPLETE = 'PRODUCT_TRANSLATION_COMPLETE';
    public const PRODUCT_TRANSLATION_NOT_COMPLETE = 'PRODUCT_TRANSLATION_NOT_COMPLETE';

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $completeness;

    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $language;

    /**
     * @param string   $completeness
     * @param Language $language
     */
    public function __construct(string $completeness, Language $language)
    {
        Assert::oneOf($completeness, [self::COMPLETE, self::NOT_COMPLETE]);

        $this->completeness = $completeness;
        $this->language = $language;
    }

    /**
     * {@inheritDoc}
     *
     * @JMS\VirtualProperty()
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public function getCompleteness(): string
    {
        return $this->completeness;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }
}
