<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Validator\Constraints;

use Ergonode\ProductCollection\Infrastructure\Validator\ProductCollectionTypeCodeUniqueValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProductCollectionTypeCodeUnique extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'The product collection type code {{ value }} is not unique.';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return ProductCollectionTypeCodeUniqueValidator::class;
    }
}
