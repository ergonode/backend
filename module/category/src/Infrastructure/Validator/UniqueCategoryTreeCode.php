<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueCategoryTreeCode extends Constraint
{
    public string $message = 'Category tree code {{ value }} is not unique.';
}
