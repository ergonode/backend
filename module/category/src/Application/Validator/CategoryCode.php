<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Validator;

use Ergonode\Category\Domain\ValueObject\CategoryCode as ValueObject;
use Ergonode\SharedKernel\Application\Validator\SystemCode;

/**
 * @Annotation
 */
class CategoryCode extends SystemCode
{
    public string $regexMessage = 'System name can have only letters, digits or underscore symbol';

    public int $min = ValueObject::MIN_LENGTH;

    public int $max = ValueObject::MAX_LENGTH;

    public string $pattern = ValueObject::PATTERN;
}
