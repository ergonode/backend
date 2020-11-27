<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Validator;

use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\SharedKernel\Application\Validator\SystemCodeConstraint;

/**
 * @Annotation
 */
class AttributeGroupCodeConstraint extends SystemCodeConstraint
{
    public string $regexMessage = 'System name can have only letters, digits or underscore symbol';

    public int $min = AttributeGroupCode::MIN_LENGTH;

    public int $max = AttributeGroupCode::MAX_LENGTH;

    public string $pattern = AttributeGroupCode::PATTERN;
}
