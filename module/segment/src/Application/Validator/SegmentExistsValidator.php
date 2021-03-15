<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Validator;

use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SegmentExistsValidator extends ConstraintValidator
{
    private SegmentRepositoryInterface $repository;

    public function __construct(SegmentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param mixed                     $value
     * @param SegmentExists|Constraint $constraint
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof SegmentExists) {
            throw new UnexpectedTypeException($constraint, SegmentExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $collection = null;
        if (SegmentId::isValid($value)) {
            $collection = $this->repository->exists(new SegmentId($value));
        }

        if (!$collection) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
