<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueConditionSetCode extends Constraint
{
    /**
     * @var string
     */
    public $validMessage = 'Given value {{ value }} is not valid condition set code';

    /**
     * @var string
     */
    public $uniqueMessage = 'Given value is not unique';
}
