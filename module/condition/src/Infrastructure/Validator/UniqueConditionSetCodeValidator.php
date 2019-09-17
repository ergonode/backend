<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Validator;

use Ergonode\Condition\Domain\Query\ConditionSetQueryInterface;
use Ergonode\Condition\Domain\ValueObject\ConditionSetCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class UniqueConditionSetCodeValidator extends ConstraintValidator
{
    /**
     * @var ConditionSetQueryInterface
     */
    private $query;

    /**
     * @param ConditionSetQueryInterface $query
     */
    public function __construct(ConditionSetQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                             $value
     * @param UniqueConditionSetCode|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueConditionSetCode) {
            throw new UnexpectedTypeException($constraint, UniqueConditionSetCode::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!ConditionSetCode::isValid($value)) {
            $this->context->buildViolation($constraint->validMessage)
                ->setParameter('{{ value }}', $value)
                ->addViolation();

            return;
        }

        if ($this->query->isExistsByCode(new ConditionSetCode($value))) {
            $this->context->buildViolation($constraint->uniqueMessage)
                ->addViolation();
        }
    }
}
