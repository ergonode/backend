<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProductCollectionTypeExists extends Constraint
{
    public string $message = 'Product collection type not exists.';
}
