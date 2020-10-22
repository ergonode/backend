<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Validator\Constraints;

use Ergonode\ProductCollection\Infrastructure\Validator\ProductCollectionCodeUniqueValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProductCollectionCodeUnique extends Constraint
{
    public string $message = 'The product collection code {{ value }} is not unique.';

    public function validatedBy(): string
    {
        return ProductCollectionCodeUniqueValidator::class;
    }
}
