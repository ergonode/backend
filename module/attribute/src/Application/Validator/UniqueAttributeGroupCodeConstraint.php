<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueAttributeGroupCodeConstraint extends Constraint
{
    public string $uniqueMessage = 'The value is not unique.';
}
