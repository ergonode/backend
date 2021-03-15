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
class Sku extends Constraint
{
    public string $message = 'Sku is too long. It should contain 255 characters or less.';
}
