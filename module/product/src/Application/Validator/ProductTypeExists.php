<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProductTypeExists extends Constraint
{
    public string $message = 'Product "{{ value }}" type does not exist.';
}
