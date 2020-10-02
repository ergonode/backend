<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Validator;

use Ergonode\Core\Application\Model\UnitFormModel;
use Ergonode\Core\Domain\Query\UnitQueryInterface;
use Ergonode\Core\Infrastructure\Validator\Constraint\UnitForm;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class UnitFormValidator extends ConstraintValidator
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
     * @param mixed               $value
     * @param UnitForm|Constraint $constraint
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UnitForm) {
            throw new UnexpectedTypeException($constraint, UnitForm::class);
        }

        if (!$value instanceof UnitFormModel) {
            throw new UnexpectedTypeException($value, UnitFormModel::class);
        }

        if (isset($value->name)) {
            $unitIdByName = $this->query->findIdByName($value->name);
            if (null !== $unitIdByName && $unitIdByName != $value->getUnitId()) {
                $this->context->buildViolation($constraint->uniqueNameMessage)
                    ->addViolation();
            }
        }
        if (isset($value->symbol)) {
            $unitIdSymbol = $this->query->findIdByCode($value->symbol);

            if (null !== $unitIdSymbol && $unitIdSymbol != $value->getUnitId()) {
                $this->context->buildViolation($constraint->uniqueSymbolMessage)
                    ->addViolation();
            }
        }
    }
}
