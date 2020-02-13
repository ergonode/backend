<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Builder;

use Ergonode\Exporter\Infrastructure\Provider\ExportProfileConstraintProvider;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 */
class ExportProfileValidatorBuilder
{
    /**
     * @var ExportProfileConstraintProvider
     */
    private ExportProfileConstraintProvider $provider;

    /**
     * ExportProfileValidatorBuilder constructor.
     *
     * @param ExportProfileConstraintProvider $provider
     */
    public function __construct(ExportProfileConstraintProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param array $data
     *
     * @return Constraint
     */
    public function build(array $data): Constraint
    {
        $resolver = function ($value, ExecutionContextInterface $context, $payload) use ($data) {
            $constraint = $this->provider->resolve($data['type'])->build($value);
            $violations = $context->getValidator()->validate($value, $constraint);
            if (0 !== $violations->count()) {
                foreach ($violations as $violation) {
                    $path = $violation->getPropertyPath();
                    $context
                        ->buildViolation($violation->getMessage(), $violation->getParameters())
                        ->atPath($path)
                        ->addViolation();
                }
            }
        };

        return new Collection(
            [
                'name' => [
                    new NotBlank(),
                ],
                'type' => [
                    new NotBlank(),
                ],
                'params' => [
                    new NotBlank(),
                    new Callback(['callback' => $resolver]),
                ],
            ]
        );
    }
}
