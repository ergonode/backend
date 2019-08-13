<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Infrastructure\Validator;

use Ergonode\TranslationDeepl\Infrastructure\Validator\Constraints\DeeplLanguageConstraint;
use Scn\DeeplApiConnector\Enum\LanguageEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class DeeplLanguageValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     *
     * @throws \ReflectionException
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof DeeplLanguageConstraint) {
            throw new UnexpectedTypeException($constraint, DeeplLanguageConstraint::class);
        }

        if (empty($value)) {
            return;
        }

        $languageEnumReflection = new \ReflectionClass(LanguageEnum::class);
        $availableLanguages = $languageEnumReflection->getConstants();

        if (!in_array($value->getCode(), $availableLanguages, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ language }}', $value)
                ->addViolation();
        }
    }
}
