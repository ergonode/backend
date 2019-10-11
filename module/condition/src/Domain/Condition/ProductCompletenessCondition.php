<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class ProductCompletenessCondition implements ConditionInterface
{
    public const TYPE = 'PRODUCT_COMPLETENESS_CONDITION';
    public const PHRASE = 'PRODUCT_COMPLETENESS_CONDITION_PHRASE';

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private $completeness;

    /**
     * @var Language
     */
    private $language;

    /**
     * @param bool     $completeness
     * @param Language $language
     */
    public function __construct(
        bool $completeness,
        Language $language
    ) {
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
     * @return bool
     */
    public function getCompleteness(): bool
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
