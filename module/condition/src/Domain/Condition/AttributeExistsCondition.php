<?php

namespace Ergonode\Condition\Domain\Condition;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

/**
 */
class AttributeExistsCondition implements ConditionInterface
{
    public const TYPE = 'ATTRIBUTE_EXISTS_CONDITION';
    public const PHRASE = 'ATTRIBUTE_EXISTS_CONDITION_PHRASE';

    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Component\Attribute\Domain\ValueObject\AttributeCode")
     */
    private $code;

    /**
     * @param AttributeCode $code
     */
    public function __construct(AttributeCode $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param array $configuration
     *
     * @return ConditionInterface
     */
    public static function createFormArray(array $configuration): ConditionInterface
    {
        return new self($configuration['code']);
    }

    /**
     * @return AttributeCode
     */
    public function getCode(): AttributeCode
    {
        return $this->code;
    }
}
