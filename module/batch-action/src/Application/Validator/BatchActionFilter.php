<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BatchActionFilter extends Constraint
{
    public string $message = 'Filters has to have set at least one of query or ids';

    /**
     * {@inheritdoc}
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
