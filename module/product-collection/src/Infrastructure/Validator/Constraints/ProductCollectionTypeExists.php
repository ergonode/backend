<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Ergonode\ProductCollection\Infrastructure\Validator\ProductCollectionTypeExistsValidator;

/**
 * @Annotation
 */
class ProductCollectionTypeExists extends Constraint
{
    public string $message = 'Product collection type not exists.';

    public function validatedBy(): string
    {
        return ProductCollectionTypeExistsValidator::class;
    }
}
