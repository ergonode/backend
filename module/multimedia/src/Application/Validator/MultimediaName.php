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
    public int $max = 128;

    public string $message = 'Multimedia name contains forbidden characters.';

    public string $messageMax = 'Multimedia name is invalid. It should contain {{ limit }} characters or less.';
}
