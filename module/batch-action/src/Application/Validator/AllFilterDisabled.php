<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AllFilterDisabled extends Constraint
{
    public string $message = 'String value can only be `all`.';
}
