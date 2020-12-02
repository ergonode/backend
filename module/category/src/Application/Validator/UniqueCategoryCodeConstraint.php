<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueCategoryCodeConstraint extends Constraint
{
    public string $uniqueMessage = 'The category code is not unique.';
}
