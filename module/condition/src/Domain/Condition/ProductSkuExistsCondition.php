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

    public const IS_EQUAL_PHRASE = 'IS_EQUAL';
    public const IS_NOT_EQUAL_PHRASE = 'IS_NOT_EQUAL';
    public const HAS_PHRASE = 'HAS';
    public const WILDCARD_PHRASE = 'WILDCARD';
    public const REGEXP_PHRASE = 'REGEXP';

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
     * @param string $operator
     * @param string $value
     */
    public function __construct(string $operator, string $value)
    {
        $this->operator = $operator;
        $this->value = $value;
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
    public static function getSupportedOperators(): array
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
