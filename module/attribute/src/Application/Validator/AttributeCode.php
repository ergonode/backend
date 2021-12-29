<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Validator;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode as ValueObject;
use Ergonode\SharedKernel\Application\Validator\SystemCode;

/**
 * @Annotation
 */
class AttributeCode extends SystemCode
{
    public string $regexMessage =
        'System name can have only letters, digits or underscore symbol and it can\'t be \'id\'';

    public int $min = ValueObject::MIN_LENGTH;

    public int $max = ValueObject::MAX_LENGTH;

    public string $pattern = ValueObject::PATTERN;
}
