<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @deprecated
 */
class AttributeCode extends Constraint
{
    public string $validMessage = 'Given value is not valid attribute code';

    public string $uniqueMessage = 'The value is not unique.';
}
