<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Validator\Constraints;

use Ergonode\ProductCollection\Infrastructure\Validator\ProductCollectionCodeValidValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProductCollectionCodeValid extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'The product collection code {{ value }} is not valid.';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return ProductCollectionCodeValidValidator::class;
    }
}
