<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Validator;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\SharedKernel\Application\Validator\SystemCodeConstraint;

/**
 * @Annotation
 */
class AttributeCodeConstraint extends SystemCodeConstraint
{
    public string $regexMessage = 'System name can have only letters, digits or underscore symbol';

    public int $min = AttributeCode::MIN_LENGTH;

    public int $max = AttributeCode::MAX_LENGTH;

    public string $pattern = AttributeCode::PATTERN;
}
