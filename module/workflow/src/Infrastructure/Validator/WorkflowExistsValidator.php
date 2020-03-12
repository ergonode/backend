<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Validator;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;

/**
 */
class WorkflowExistsValidator extends ConstraintValidator
{
    /**
     * @var WorkflowProvider
     */
    private WorkflowProvider $provider;

    /**
     * @param WorkflowProvider $provider
     */
    public function __construct(WorkflowProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof WorkflowExists) {
            throw new UnexpectedTypeException($constraint, WorkflowExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $workflow = $this->provider->provide();

        if ($workflow) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
