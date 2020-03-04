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
class SkusValid extends Constraint
{
    /**
     * @var string
     */
    public string $notExistsMessage = 'Sku {{ value }} doesn\'t exist.';

    /**
     * @var string
     */
    public string $invalidMessage = 'Sku {{ value }} is not valid.';
}
