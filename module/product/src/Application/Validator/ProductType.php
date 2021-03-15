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
class ProductType extends Constraint
{
    public string $message = 'Incorrect product type.';

    /**
     * @var array|null
     */
    public ?array $type = [];
}
