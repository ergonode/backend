<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class StatusExists extends Constraint
{
    public string $message = 'Status not exists';
}
