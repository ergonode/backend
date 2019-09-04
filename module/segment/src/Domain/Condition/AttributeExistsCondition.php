<?php

namespace Ergonode\Segment\Domain\Specification;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Segment\Domain\Condition\ConditionInterface;

/**
 */
class AttributeExistsCondition implements ConditionInterface
{
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
