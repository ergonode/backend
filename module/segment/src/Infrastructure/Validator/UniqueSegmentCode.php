<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueSegmentCode extends Constraint
{
    public string $validMessage = 'Given value {{ value }} is not valid segment code';

    public string $uniqueMessage = 'Given value is not unique';
}
