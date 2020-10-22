<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Validator;

use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueSegmentCodeValidator extends ConstraintValidator
{
    /**
     * @var SegmentQueryInterface
     */
    private SegmentQueryInterface $query;

    /**
     * @param SegmentQueryInterface $query
     */
    public function __construct(SegmentQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                        $value
     * @param UniqueSegmentCode|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueSegmentCode) {
            throw new UnexpectedTypeException($constraint, UniqueSegmentCode::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!SegmentCode::isValid($value)) {
            $this->context->buildViolation($constraint->validMessage)
                ->setParameter('{{ value }}', $value)
                ->addViolation();

            return;
        }

        if ($this->query->isExistsByCode(new SegmentCode($value))) {
            $this->context->buildViolation($constraint->uniqueMessage)
                ->addViolation();
        }
    }
}
