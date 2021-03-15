<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class RoleNameUnique extends Constraint
{
    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public string $uniqueMessage = 'The role name should be unique.';
}
