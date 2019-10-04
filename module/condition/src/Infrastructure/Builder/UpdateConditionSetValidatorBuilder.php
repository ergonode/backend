<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Builder;

use Ergonode\Condition\Infrastructure\Resolver\ConditionConstraintResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 */
class UpdateConditionSetValidatorBuilder
{
    /**
     * @var ConditionConstraintResolver
     */
    private $conditionConstraintResolver;

    /**
     * @param ConditionConstraintResolver $conditionConstraintResolver
     */
    public function __construct(ConditionConstraintResolver $conditionConstraintResolver)
    {
        $this->conditionConstraintResolver = $conditionConstraintResolver;
    }

    /**
     * @param array $data
     *
     * @return Constraint
     */
    public function build(array $data): Constraint
    {
        $resolver = function ($data, ExecutionContextInterface $context, $payload) {
            foreach ($data as $index => $condition) {
                if (!is_array($condition)) {
                    throw new \InvalidArgumentException('Condition in condition set must be array type');
                }

                if (!array_key_exists('type', $condition)) {
                    throw new \InvalidArgumentException('Type not found in condition');
                }

                $constraint = $this->conditionConstraintResolver->resolve($condition['type'])->build($condition);
                unset($condition['type']);
                $violations = $context->getValidator()->validate($condition, $constraint);
                if (0 !== $violations->count()) {
                    /** @var ConstraintViolation $violation */
                    foreach ($violations as $violation) {
                        $path = sprintf('[%d]%s', $index, $violation->getPropertyPath());
                        $context
                            ->buildViolation($violation->getMessage(), $violation->getParameters())
                            ->atPath($path)
                            ->addViolation();
                    }
                }
            }
        };

        return new Collection([
            'fields' => [
                'name' => [
                    new Optional([
                        new NotBlank(),
                        new All([
                            new Length(['min' => 2, 'max' => 255]),
                        ]),
                    ]),
                ],
                'description' => [
                    new Optional([
                        new NotBlank(),
                        new All([
                            new Length(['max' => 255]),
                        ]),
                    ]),
                ],
                'conditions' => [
                    new Callback(['callback' => $resolver]),
                ],
            ],
        ]);
    }
}
