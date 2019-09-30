<?php

namespace Ergonode\Condition\Domain\Service\Strategy\Calculator;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\Condition\Domain\Condition\NumericAttributeValueCondition;
use Ergonode\Condition\Domain\Service\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;

/**
 */
class NumericAttributeValueConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private $repository;

    /**
     * @param AttributeRepositoryInterface $repository
     */
    public function __construct(AttributeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function isSupportedBy(string $type): bool
    {
        return NumericAttributeValueCondition::TYPE === $type;
    }

    /**
     * @param AbstractProduct                                   $object
     * @param NumericAttributeValueCondition|ConditionInterface $configuration
     *
     * @return bool
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        $attributeId = $configuration->getAttribute();

        $attribute = $this->repository->load($attributeId);

        Assert::notNull($attribute);
        $option = $configuration->getOption();
        $expected = $configuration->getValue();

        if ($object->hasAttribute($attribute->getCode())) {
            $value = (float) $object->getAttribute($attribute->getCode())->getValue();
            if (('=' === $option) && $value !== $expected) {
                return false;
            }

            if (('<>' === $option) && $value === $expected) {
                return false;
            }

            if (('>' === $option) && $value <= $expected) {
                return false;
            }

            if (('>=' === $option) && $value < $expected) {
                return false;
            }

            if (('<' === $option) && $value >= $expected) {
                return false;
            }

            if (('<=' === $option) && $value > $expected) {
                return false;
            }
        }

        return true;
    }
}
