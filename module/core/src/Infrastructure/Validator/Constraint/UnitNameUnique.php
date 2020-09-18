<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Validator\Constraint;

use Ergonode\Core\Infrastructure\Validator\UnitNameUniqueValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UnitNameUnique extends Constraint
{
    /**
     * @var string
     */
    public string $uniqueMessage = 'The unit name should be unique.';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return UnitNameUniqueValidator::class;
    }
}
