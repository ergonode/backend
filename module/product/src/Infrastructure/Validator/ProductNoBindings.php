<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProductNoBindings extends Constraint
{
    public string $message = 'Can\'t add child, parent product doesn\'t have binding attributes.';
}
