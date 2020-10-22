<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Validator\Constraint;

use Ergonode\Core\Infrastructure\Validator\LanguageCodeExistsValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LanguageCodeExists extends Constraint
{
    public string $message = 'Language code {{ value }} does not exists.';

    public function validatedBy(): string
    {
        return LanguageCodeExistsValidator::class;
    }
}
