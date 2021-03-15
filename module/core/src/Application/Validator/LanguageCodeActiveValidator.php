<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Validator;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class LanguageCodeActiveValidator extends ConstraintValidator
{
    private LanguageQueryInterface $query;

    public function __construct(LanguageQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof LanguageCodeActive) {
            throw new UnexpectedTypeException($constraint, LanguageCodeActive::class);
        }

        if (empty($value)) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!in_array($value, $this->query->getDictionaryActive(), true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
