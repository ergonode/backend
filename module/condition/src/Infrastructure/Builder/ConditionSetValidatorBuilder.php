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
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 */
class ConditionSetValidatorBuilder
{
    public const CREATE_GROUP = 'conditionSetCreateGroup';
    public const UPDATE_GROUP = 'conditionSetUpdateGroup';
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
                $pathPrefix = sprintf('%d', $index);

                if (!is_array($condition)) {
                    $context
                        ->buildViolation('Conditions must be an collection')
                        ->atPath($pathPrefix)
                        ->addViolation();
                }

                if (!array_key_exists('type', $condition)) {
                    $context
                        ->buildViolation('Type not found in condition')
                        ->atPath($pathPrefix)
                        ->addViolation();
                }

                if (!array_key_exists('parameters', $condition)) {
                    $context
                        ->buildViolation('Parameters not found in condition')
                        ->atPath($pathPrefix)
                        ->addViolation();
                }

                if (0 === $context->getViolations()->count()) {
                    $constraint = $this->conditionConstraintResolver->resolve($condition['type']);

                    $violations = $context->getValidator()->validate($condition['parameters'], $constraint);

                    if (0 !== $violations->count()) {
                        /** @var ConstraintViolation $violation */
                        foreach ($violations as $violation) {
                            $context
                                ->buildViolation($violation->getMessage(), $violation->getParameters())
                                ->atPath($pathPrefix)
                                ->addViolation();
                        }
                    }
                }
            }
        };

        return new Collection([
            'code' => [
                new NotBlank(['groups' => self::CREATE_GROUP]),
                new Length(['min' => 2, 'max' => 100, 'groups' => self::CREATE_GROUP]),
            ],
            'name' => [
                new NotBlank(),
                new All([
                    new Length(['min' => 2, 'max' => 255]),
                ]),
            ],
            'description' => [
                new NotBlank(),
                new All([
                    new Length(['max' => 255]),
                ]),
            ],
            'conditions' => [
                new NotBlank(['groups' => self::UPDATE_GROUP]),
                new Callback(['callback' => $resolver, 'groups' => self::UPDATE_GROUP]),
            ],
        ]);
    }
}
