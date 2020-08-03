<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Validator\Constraint;

use Ergonode\Core\Infrastructure\Validator\LanguageCodeActiveValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LanguageCodeActive extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'Language code {{ value }} is not active';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return LanguageCodeActiveValidator::class;
    }
}
