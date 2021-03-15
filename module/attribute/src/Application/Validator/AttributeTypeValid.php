<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AttributeTypeValid extends Constraint
{
    public string $message = 'Attribute {{ value }} not valid.';

    public ?string $type = null;
}
