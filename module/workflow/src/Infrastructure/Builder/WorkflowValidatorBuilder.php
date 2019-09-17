<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Builder;

use Ergonode\Workflow\Infrastructure\Validator\StatusNotExists;
use Ergonode\Workflow\Infrastructure\Validator\WorkflowExists;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 */
class WorkflowValidatorBuilder
{
    public const UNIQUE_WORKFLOW = 'WorkflowExists';

    public const GROUPS = [
        self::UNIQUE_WORKFLOW,
    ];

    /**
     * @var TransitionValidatorBuilder
     */
    private $transitionBuilder;

    /**
     * @param TransitionValidatorBuilder $transitionBuilder
     */
    public function __construct(TransitionValidatorBuilder $transitionBuilder)
    {
        $this->transitionBuilder = $transitionBuilder;
    }

    /**
     * @param array $data
     *
     * @return Constraint
     */
    public function build(array $data): Constraint
    {
        $uniqueName = static function ($data, ExecutionContextInterface $context, $payload) {
            $column = array_column($data, 'name');
            $values = array_count_values($column);

            foreach ($values as $key => $value) {
                if ($value > 1) {
                    foreach ($column as $index => $name) {
                        if ($key === $name) {
                            $context->buildViolation('The name is not unique')
                                ->atPath('['.$index.'][name]')
                                ->addViolation();
                        }
                    }
                }
            }
        };

        $uniqueTransition = static function ($data, ExecutionContextInterface $context, $payload) {
            $result = [];
            foreach ($data as $transition) {
                $key = $transition['source'].$transition['destination'];
                key_exists($key, $result) ? $result[$key]++ : $result[$key] = 1;
            }

            foreach ($data as $index => $transition) {
                $key = $transition['source'].$transition['destination'];
                if ($result[$key] > 1) {
                    $context->buildViolation('Transition with this configuration already exists')
                        ->atPath(' ['.$index.'][source] ')
                        ->addViolation();
                    $context->buildViolation('Transition with this configuration already exists')
                        ->atPath('['.$index.'][destination]')
                        ->addViolation();
                }
            }
        };

        return new Collection(
            [
                'code' => [
                    new NotBlank(),
                    new Length(['max' => 100]),
                    new WorkflowExists(['groups' => self::UNIQUE_WORKFLOW]),
                ],
                'statuses' => new All(
                    [
                        'constraints' => [
                            new NotBlank(),
                            new Uuid(),
                            new StatusNotExists(),
                        ],
                    ]
                ),
                'transitions' => [
                    new Callback($uniqueTransition),
                    new Callback($uniqueName),
                    new All(
                        [
                            'constraints' => [
                                $this->transitionBuilder->build($data, $data['statuses']),
                            ],
                        ]
                    ),
                ],
            ]
        );
    }
}
