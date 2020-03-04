<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Infrastructure\Validator\Constraints;

use Ergonode\TranslationDeepl\Infrastructure\Validator\DeeplLanguageValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DeeplLanguageConstraint extends Constraint
{
    /**
     * @var string
     */
    public string $message = '"{{ language }}" language is not supported';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return DeeplLanguageValidator::class;
    }
}
