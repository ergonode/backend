<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConditionSetExists extends Constraint
{
    public string $message = 'Condition set {{ value }} not exists.';
}
