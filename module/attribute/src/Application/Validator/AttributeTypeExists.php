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
class AttributeTypeExists extends Constraint
{
    public string $message = 'Attribute type {{ value }} not exists.';
}
