<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Validator;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class LanguageCodeExistsValidator extends ConstraintValidator
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
        if (!$constraint instanceof LanguageCodeExists) {
            throw new UnexpectedTypeException($constraint, LanguageCodeExists::class);
        }

        if (!is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }
        foreach (array_keys($value) as $languageCode) {
            if (!in_array($languageCode, $this->query->getDictionary(), true)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $languageCode)
                    ->addViolation();
            }
        }
    }
}
