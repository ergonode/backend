<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);


namespace Ergonode\Condition\Infrastructure\Condition\Validator;

use Ergonode\Condition\Domain\Condition\ProductSkuExistsCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 */
class ProductSkuExistsConditionValidatorStrategy implements ConditionValidatorStrategyInterface
{
    /**
     * @inheritDoc
     */
    public function supports(string $type): bool
    {
        return $type === ProductSkuExistsCondition::TYPE;
    }

    /**
     * @inheritDoc
     */
    public function build(array $data): Constraint
    {
        return new Assert\Collection(
            [
                'operator' => [
                    new Assert\NotBlank(),
                    new Assert\Choice(ProductSkuExistsCondition::getChoices()),
                ],
                'value' => [
                    new Assert\NotBlank(),
                    new Assert\Callback([$this, 'regexpValidate']),
                    new Assert\Callback([$this, 'wildcardValidate']),
                ],
            ]
        );
    }

    /**
     * @param mixed                     $value
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function wildcardValidate($value, ExecutionContextInterface $context, $payload)
    {
        $operator = $context->getRoot()['operator'];
        if (ProductSkuExistsCondition::WILDCARD !== $operator) {
            return;
        }

        try {
            fnmatch($value, "");
        } catch (\Throwable $exception) {
            $context->buildViolation(trim($exception->getMessage()))
                ->addViolation();
        }
    }

    /**
     * @param mixed                     $value
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function regexpValidate($value, ExecutionContextInterface $context, $payload)
    {
        $operator = $context->getRoot()['operator'];
        if (ProductSkuExistsCondition::REGEXP !== $operator) {
            return;
        }

        try {
            preg_match($value, "");
        } catch (\Throwable $exception) {
            $message = substr(
                $exception->getMessage(),
                strpos($exception->getMessage(), 'preg_match():') + strlen('preg_match():')
            );
            $context->buildViolation(trim($message))
                ->addViolation();
        }
    }
}
