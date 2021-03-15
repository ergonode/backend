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
class LanguagePrivilegesRelations extends Constraint
{
    public string $message = 'Read language privilege for {{ value }} must be set when edit is activated';
}
