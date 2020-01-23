<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Condition\Domain\ConditionInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductSkuExistsCondition implements ConditionInterface
{
    public const TYPE = 'PRODUCT_SKU_EXISTS_CONDITION';
    public const PHRASE = 'PRODUCT_SKU_EXISTS_CONDITION_PHRASE';


    public const IS_EQUAL = '=';
    public const IS_NOT_EQUAL = '<>';
    public const HAS = 'HAS';
    public const WILDCARD = 'WILDCARD';
    public const REGEXP = 'REGEXP';

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $operator;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $value;

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
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string[]
     */
    public static function getChoices(): array
    {
        return
            [
                self::IS_EQUAL,
                self::IS_NOT_EQUAL,
                self::HAS,
                self::WILDCARD,
                self::REGEXP,
            ];
    }
}
