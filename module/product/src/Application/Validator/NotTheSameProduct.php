<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotTheSameProduct extends Constraint
{
    public string $message = 'Can\'t add relation to {{ value }}. This is the same product.';

    public ?string $aggregateId = null;
}
