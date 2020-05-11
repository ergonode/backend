<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Validator\Constraint;

use Ergonode\Core\Infrastructure\Validator\LanguageIdValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LanguageIdExists extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'LanguageId {{ value }} not exists.';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return LanguageIdValidator::class;
    }
}
