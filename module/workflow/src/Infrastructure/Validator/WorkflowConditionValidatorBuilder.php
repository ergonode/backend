<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Ergonode\Workflow\Infrastructure\Provider\WorkflowConditionValidatorProvider;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Callback;

class WorkflowConditionValidatorBuilder
{
    private WorkflowConditionValidatorProvider $provider;

    public function __construct(WorkflowConditionValidatorProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param array $data
     */
    public function build(array $data): Constraint
    {
        $resolver = function ($data, ExecutionContextInterface $context, $payload): void {
            foreach ($data as $index => $condition) {
                if (!is_array($condition)) {
                    throw new \InvalidArgumentException('Condition in condition set must be array type');
                }

                if (!array_key_exists('type', $condition)) {
                    throw new \InvalidArgumentException('Type not found in condition');
                }

                $constraint = $this->provider->resolve($condition['type'])->build();
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
                'conditions' => [
                    new Callback(['callback' => $resolver]),
                ],
            ],
        ]);
    }
}
