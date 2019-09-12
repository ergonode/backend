<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Builder;

use Symfony\Component\Validator\Constraint;

/**
 */
interface ConditionValidatorBuilderInterface
{
    public function build(array $data): Constraint;
}
