<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Validator;

use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class StatusCodeUniqueValidator extends ConstraintValidator
{
    private StatusRepositoryInterface $repository;

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
        if (!$constraint instanceof StatusCodeUnique) {
            throw new UnexpectedTypeException($constraint, StatusCodeUnique::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $status = null;
        if (StatusCode::isValid($value)) {
            $status = $this->repository->load(StatusId::fromCode((new StatusCode($value))->getValue()));
        }

        if ($status) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
