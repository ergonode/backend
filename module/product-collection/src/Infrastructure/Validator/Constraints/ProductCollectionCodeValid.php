<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Validator\Constraints;

use Ergonode\ProductCollection\Infrastructure\Validator\ProductCollectionCodeValidValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @deprecated
 */
class ProductCollectionCodeValid extends Constraint
{
    public string $message = 'The product collection code {{ value }} is not valid.';

    public function validatedBy(): string
    {
        return ProductCollectionCodeValidValidator::class;
    }
}
