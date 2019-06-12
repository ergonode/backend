<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CategoryCode extends Constraint
{
    /**
     * @var string
     */
    public $validMessage = 'The category code is not valid.';

    /**
     * @var string
     */
    public $uniqueMessage = 'The category code is not unique.';
}
