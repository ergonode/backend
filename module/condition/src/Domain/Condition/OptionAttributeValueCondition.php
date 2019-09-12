<?php

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

/**
 */
class OptionAttributeValueCondition implements ConditionInterface
{
    public const TYPE = 'OPTION_ATTRIBUTE_VALUE_CONDITION';
    public const PHRASE = 'OPTION_ATTRIBUTE_VALUE_CONDITION_PHRASE';

    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Component\Attribute\Domain\ValueObject\AttributeCode")
     */
    private $code;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $value;

    /**
     * @param AttributeCode $code
     * @param string        $value
     */
    public function __construct(AttributeCode $code, string $value)
    {
        $this->code = $code;
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public static function createFormArray(array $configuration): ConditionInterface
    {
        return new self($configuration['code'], $configuration['value']);
    }

    /**
     * @return AttributeCode
     */
    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
