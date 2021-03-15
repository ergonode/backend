<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UnitForm extends Constraint
{
    public string $uniqueNameMessage = 'The unit name should be unique.';

    public string $emptyNameMessage = 'The unit name should not be empty.';

    public string $uniqueSymbolMessage = 'The unit symbol should be unique.';

    public string $emptySymbolMessage = 'The unit symbol should not be empty.';

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
