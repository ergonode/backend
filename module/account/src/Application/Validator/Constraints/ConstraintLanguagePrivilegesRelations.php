<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintLanguagePrivilegesRelations extends Constraint
{
    public string $message = 'Read language privilege for {{ value }} must be set when edit is activated';

    public function validatedBy(): string
    {
        return ConstraintLanguagePrivilegesRelationsValidator::class;
    }
}
