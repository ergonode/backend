<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintPrivilegeRelations extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Read privilege must be set when create, update or delete is activated';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return ConstraintPrivilegeRelationsValidator::class;
    }
}
