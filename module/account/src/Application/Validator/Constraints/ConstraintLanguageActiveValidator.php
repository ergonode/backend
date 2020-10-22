<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Validator\Constraints;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ConstraintLanguageActiveValidator extends ConstraintValidator
{
    private LanguageQueryInterface $query;

    /**
     * @param LanguageQueryInterface $query
     */
    public function __construct(LanguageQueryInterface $query)
    {
        $this->query = $query;
    }


    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ConstraintLanguageActive) {
            throw new UnexpectedTypeException($constraint, ConstraintLanguageActive::class);
        }

        if (!is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }
        foreach (array_keys($value) as $languageCode) {
            if (!in_array($languageCode, $this->query->getDictionaryActive(), true)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $languageCode)
                    ->addViolation();
            }
        }
    }
}
