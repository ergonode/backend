<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use JMS\Serializer\Annotation as JMS;

class ProductHasStatusCondition implements ConditionInterface
{
    public const TYPE = 'PRODUCT_HAS_STATUS_CONDITION';
    public const PHRASE = 'PRODUCT_HAS_STATUS_CONDITION_PHRASE';

    public const HAS = 'HAS';
    public const NOT_HAS = 'NOT_HAS';

    /**
     * @JMS\Type("string")
     */
    private string $operator;

    /**
     * @var StatusId[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\StatusId>")
     */
    private array $value;

    /**
     * @var Language[]
     *
     * @JMS\Type("array<Ergonode\Core\Domain\ValueObject\Language>")
     */
    private array $language;

    /**
     * @param StatusId[] $value
     * @param Language[] $language
     */
    public function __construct(string $operator, array $value, array $language)
    {
        $this->operator = $operator;
        $this->value = $value;
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

    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @return StatusId[]
     */
    public function getValue(): array
    {
        return $this->value;
    }

    /**
     * @return Language[]
     */
    public function getLanguage(): array
    {
        return $this->language;
    }

    /**
     * @return string[]
     */
    public static function getSupportedOperators(): array
    {
        return
            [
                self::HAS,
                self::NOT_HAS,
            ];
    }
}
