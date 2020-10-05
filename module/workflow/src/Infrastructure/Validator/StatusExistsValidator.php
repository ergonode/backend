<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Validator;

use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class StatusExistsValidator extends ConstraintValidator
{
    /**
     * @var StatusRepositoryInterface
     */
    private StatusRepositoryInterface $repository;

    /**
     * @param StatusRepositoryInterface $repository
     */
    public function __construct(StatusRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param mixed                     $value
     * @param WorkflowExists|Constraint $constraint
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof StatusExists) {
            throw new UnexpectedTypeException($constraint, StatusExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $status = null;
        if (Statusid::isValid($value)) {
            $status = $this->repository->load(new StatusId($value));
        }

        if ($status instanceof Status) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
