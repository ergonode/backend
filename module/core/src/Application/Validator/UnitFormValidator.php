<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Validator;

use Ergonode\Core\Application\Model\UnitFormModel;
use Ergonode\Core\Domain\Query\UnitQueryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UnitFormValidator extends ConstraintValidator
{
    private UnitQueryInterface $query;

    public function __construct(UnitQueryInterface $query)
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
        if (!$constraint instanceof UnitForm) {
            throw new UnexpectedTypeException($constraint, UnitForm::class);
        }

        if (!$value instanceof UnitFormModel) {
            throw new UnexpectedTypeException($value, UnitFormModel::class);
        }
        $this->validateName($value, $constraint);
        $this->validateSymbol($value, $constraint);
    }

    private function validateName(UnitFormModel $value, UnitForm $constraint): void
    {
        if (!isset($value->name) || null === $value->name) {
            $this->context->buildViolation($constraint->emptyNameMessage)
                ->atPath('name')
                ->addViolation();

            return;
        }
        $unitIdByName = $this->query->findIdByName($value->name);
        // phpcs:ignore
        if (null !== $unitIdByName && $unitIdByName != $value->getUnitId()) {
            $this->context->buildViolation($constraint->uniqueNameMessage)
                ->atPath('name')
                ->addViolation();
        }
    }

    private function validateSymbol(UnitFormModel $value, UnitForm $constraint): void
    {
        if (!isset($value->symbol) || null === $value->symbol) {
            $this->context->buildViolation($constraint->emptySymbolMessage)
                ->atPath('symbol')
                ->addViolation();

            return;
        }
        $unitIdBySymbol = $this->query->findIdByCode($value->symbol);
        if (null !== $unitIdBySymbol && $unitIdBySymbol !== $value->getUnitId()) {
            $this->context->buildViolation($constraint->uniqueSymbolMessage)
                ->atPath('symbol')
                ->addViolation();
        }
    }
}
