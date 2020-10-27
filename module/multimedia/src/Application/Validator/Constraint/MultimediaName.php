<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MultimediaName extends Constraint
{
    public string $message = 'Multimedia name is too long. It should contain {{ limit }} characters or less.';

    public int $max;

    public function validatedBy(): string
    {
        return MultimediaNameValidator::class;
    }
}
