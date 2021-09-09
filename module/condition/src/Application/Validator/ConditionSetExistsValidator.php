<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\Validator;

use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ConditionSetExistsValidator extends ConstraintValidator
{
    private ConditionSetRepositoryInterface $conditionSetRepository;

    public function __construct(ConditionSetRepositoryInterface $conditionSetRepository)
    {
        $this->conditionSetRepository = $conditionSetRepository;
    }

    /**
     * @param mixed      $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ConditionSetExists) {
            throw new UnexpectedTypeException($constraint, ConditionSetExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $conditionSet = false;
        if (ConditionSetId::isValid($value)) {
            $conditionSet = $this->conditionSetRepository->exists(new ConditionSetId($value));
        }

        if (!$conditionSet) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
