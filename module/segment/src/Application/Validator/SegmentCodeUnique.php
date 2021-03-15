<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SegmentCodeUnique extends Constraint
{
    public string $uniqueMessage = 'Given value is not unique';
}
