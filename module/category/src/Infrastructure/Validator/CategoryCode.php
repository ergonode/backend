<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CategoryCode extends Constraint
{
    public string $validMessage = 'The category code is not valid.';

    public string $uniqueMessage = 'The category code is not unique.';
}
