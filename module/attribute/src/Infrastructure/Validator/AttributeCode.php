<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AttributeCode extends Constraint
{
    /**
     * @var string
     */
    public $validMessage = 'Given value is not valid attribute code';

    /**
     * @var string
     */
    public $uniqueMessage = 'The value is not unique.';
}
