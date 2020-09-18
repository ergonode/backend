<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Validator;

use Ergonode\Core\Application\Model\UnitFormModel;
use Ergonode\Core\Domain\Query\UnitQueryInterface;
use Ergonode\Core\Infrastructure\Validator\Constraint\UnitSymbolUnique;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class UnitSymbolUniqueValidator extends ConstraintValidator
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
     * @param mixed                       $value
     * @param UnitSymbolUnique|Constraint $constraint
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UnitSymbolUnique) {
            throw new UnexpectedTypeException($constraint, UnitSymbolUnique::class);
        }

        if (!$value instanceof UnitFormModel) {
            throw new UnexpectedTypeException($value, UnitFormModel::class);
        }

        if (null === $value->symbol) {
            return;
        }

        $unitId = $this->query->findIdByCode($value->symbol);

        if (null !== $unitId && $unitId != $value->getUnitId()) {
            $this->context->buildViolation($constraint->uniqueMessage)
                ->addViolation();
        }
    }
}
