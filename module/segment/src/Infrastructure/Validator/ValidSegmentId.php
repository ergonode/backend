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
class ValidSegmentId extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'The Segment id {{ value }} is not valid.';
}
