<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Validator;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\SharedKernel\Application\Validator\SystemCodeConstraint;

/**
 * @Annotation
 */
class CategoryCodeConstraint extends SystemCodeConstraint
{
    public string $regexMessage = 'System name can have only letters, digits or underscore symbol';

    public int $min = CategoryCode::MIN_LENGTH;

    public int $max = CategoryCode::MAX_LENGTH;

    public string $pattern = CategoryCode::PATTERN;
}
