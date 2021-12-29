<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MultimediaName extends Constraint
{
    public string $message = 'Multimedia name is too long. It should contain {{ limit }} characters or less.';

    public int $max;
}
