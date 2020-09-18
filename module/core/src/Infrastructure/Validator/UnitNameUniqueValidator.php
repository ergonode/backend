<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Validator;

use Ergonode\Core\Domain\Query\UnitQueryInterface;
use Ergonode\Core\Infrastructure\Validator\Constraint\UnitNameUnique;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class UnitNameUniqueValidator extends ConstraintValidator
{
    /**
     * @var UnitQueryInterface
     */
    private UnitQueryInterface $query;

    /**
     * @param UnitQueryInterface $query
     */
    public function __construct(UnitQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                     $value
     * @param UnitNameUnique|Constraint $constraint
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UnitNameUnique) {
            throw new UnexpectedTypeException($constraint, UnitNameUnique::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $unitId = $this->query->findIdByName($value);

        if ($unitId) {
            $this->context->buildViolation($constraint->uniqueMessage)
                ->addViolation();
        }
    }
}
