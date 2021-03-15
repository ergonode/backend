<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\TranslationDeepl\Application\Validator;

use Scn\DeeplApiConnector\Enum\LanguageEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DeeplLanguageAvailableValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     *
     * @throws \ReflectionException
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof DeeplLanguageAvailable) {
            throw new UnexpectedTypeException($constraint, DeeplLanguageAvailable::class);
        }

        if (empty($value)) {
            return;
        }

        $languageEnumReflection = new \ReflectionClass(LanguageEnum::class);
        $availableLanguages = $languageEnumReflection->getConstants();

        if (!in_array($value, $availableLanguages, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ language }}', $value)
                ->addViolation();
        }
    }
}
