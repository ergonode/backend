<?php

namespace Ergonode\Condition\Domain\Service\Strategy\Calculator;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\Condition\Domain\Condition\TextAttributeValueCondition;
use Ergonode\Condition\Domain\Service\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;

/**
 */
class TextAttributeValueConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
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
    public function supports(string $type): bool
    {
        return TextAttributeValueCondition::TYPE === $type;
    }

    /**
     * @param AbstractProduct                                $object
     * @param TextAttributeValueCondition|ConditionInterface $configuration
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
            $value = $object->getAttribute($attribute->getCode())->getValue();
            if ('=' === $option && $value !== $expected) {
                return false;
            }

            if ('~' === $option && false === strpos($value, $expected)) {
                return false;
            }
        }

        return true;
    }
}
