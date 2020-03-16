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
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Domain\Query\WorkflowQueryInterface;

/**
 */
class WorkflowExistsValidator extends ConstraintValidator
{
    /**
     * @var WorkflowQueryInterface
     */
    private WorkflowQueryInterface $query;

    /**
     * @param WorkflowQueryInterface $query
     */
    public function __construct(WorkflowQueryInterface $query)
    {
        $this->query = $query;
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

        $workflowId = $this->query->findWorkflowIdByCode($value);

        if ($workflowId) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
