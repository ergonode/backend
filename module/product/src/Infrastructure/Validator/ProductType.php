<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProductType extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'Incorrect product type.';

    /**
     * @var string|null
     */
    public ?string $type = null;
}
