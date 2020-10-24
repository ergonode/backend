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
class ConstraintPrivilegeRelations extends Constraint
{
    public string $message = 'Read privilege must be set when create, update or delete is activated';

    public function validatedBy(): string
    {
        return ConstraintPrivilegeRelationsValidator::class;
    }
}
