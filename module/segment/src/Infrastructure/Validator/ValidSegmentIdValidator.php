<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Validator;

use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 */
class ValidSegmentIdValidator extends ConstraintValidator
{
    /**
     * @var SegmentRepositoryInterface
     */
    private SegmentRepositoryInterface $repository;

    /**
     * @param SegmentRepositoryInterface $repository
     */
    public function __construct(SegmentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param mixed                     $value
     * @param ValidSegmentId|Constraint $constraint
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidSegmentId) {
            throw new UnexpectedTypeException($constraint, ValidSegmentId::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $collection = $this->repository->exists(new SegmentId($value));

        if (!$collection) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
