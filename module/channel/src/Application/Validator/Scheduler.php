<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Scheduler extends Constraint
{
    public string $message = 'Recurrence cannot be lesser than 1 minute';

    /**
     * {@inheritdoc}
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
