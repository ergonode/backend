<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class AttributeFormModelValidator extends ConstraintValidator
{
    /**
     * @param mixed                    $value
     * @param AttributeCode|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AttributeFormModel) {
            throw new UnexpectedTypeException($constraint, AttributeFormModel::class);
        }

        if (!$value instanceof \Ergonode\Attribute\Application\Form\Model\CreateAttributeFormModel) {
            throw new UnexpectedTypeException($value, \Ergonode\Attribute\Application\Form\Model\CreateAttributeFormModel::class);
        }

        $this->context->buildViolation($constraint->message)
            ->atPath('configuration')
            ->setParameter('{{ value }}', $value->type)
            ->addViolation();
    }
}
