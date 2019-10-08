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
    /**
     * @var string
     */
    public $validMessage = 'Given value {{ value }} is not valid segment code';

    /**
     * @var string
     */
    public $uniqueMessage = 'Given value is not unique';
}
