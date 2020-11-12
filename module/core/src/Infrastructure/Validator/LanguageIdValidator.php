<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Validator;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Infrastructure\Validator\Constraint\LanguageIdExists;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class LanguageIdValidator extends ConstraintValidator
{
    private LanguageQueryInterface $query;

    public function __construct(LanguageQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                       $value
     * @param Constraint|LanguageIdExists $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof LanguageIdExists) {
            throw new UnexpectedTypeException($constraint, LanguageIdExists::class);
        }


        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $result = $this->query->getLanguageById($value);

        if (!$result) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
