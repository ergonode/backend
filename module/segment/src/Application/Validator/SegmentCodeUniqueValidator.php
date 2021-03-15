<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Validator;

use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class SegmentCodeUniqueValidator extends ConstraintValidator
{
    private SegmentQueryInterface $query;

    public function __construct(SegmentQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                        $value
     * @param SegmentCodeUnique|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof SegmentCodeUnique) {
            throw new UnexpectedTypeException($constraint, SegmentCodeUnique::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!SegmentCode::isValid($value)) {
            return;
        }

        if ($this->query->isExistsByCode(new SegmentCode($value))) {
            $this->context->buildViolation($constraint->uniqueMessage)
                ->addViolation();
        }
    }
}
