<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Validator\Constraint;

use Ergonode\Core\Infrastructure\Validator\LanguageCodeValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LanguageCodeConstraint extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'The language code {{ language }} is not valid.';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return LanguageCodeValidator::class;
    }
}
