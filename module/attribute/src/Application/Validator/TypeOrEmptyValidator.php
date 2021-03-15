<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TypeOrEmptyValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof TypeOrEmpty) {
            throw new UnexpectedTypeException($constraint, TypeOrEmpty::class);
        }

        if (null === $value) {
            return;
        }

        $types = (array) $constraint->type;

        if (empty($value)) {
            return;
        }

        foreach ($types as $type) {
            $type = strtolower($type);
            $type = 'boolean' === $type ? 'bool' : $type;
            $isFunction = 'is_'.$type;
            $ctypeFunction = 'ctype_'.$type;
            if (\function_exists($isFunction) && $isFunction($value)) {
                return;
            }
            if (\function_exists($ctypeFunction) && $ctypeFunction($value)) {
                return;
            }
            if ($value instanceof $type) {
                return;
            }
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $this->formatValue($value))
            ->setParameter('{{ type }}', implode('|', $types))
            ->setCode(Type::INVALID_TYPE_ERROR)
            ->addViolation();
    }
}
