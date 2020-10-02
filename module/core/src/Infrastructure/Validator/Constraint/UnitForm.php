<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Validator\Constraint;

use Ergonode\Core\Infrastructure\Validator\UnitFormValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UnitForm extends Constraint
{
    /**
     * @var string
     */
    public string $uniqueNameMessage = 'The unit name should be unique.';

    /**
     * @var string
     */
    public string $emptyNameMessage = 'The unit name should not be empty.';

    /**
     * @var string
     */
    public string $uniqueSymbolMessage = 'The unit symbol should be unique.';

    /**
     * @var string
     */
    public string $emptySymbolMessage = 'The unit symbol should not be empty.';


    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return UnitFormValidator::class;
    }
}
