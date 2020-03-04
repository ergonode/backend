<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class StatusIdNotExists extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'Status Id {{ value }} does not exists';
}
