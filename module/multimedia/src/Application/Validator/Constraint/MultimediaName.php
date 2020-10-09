<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MultimediaName extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'Multimedia name is to long, It should have {{ limit }} character or less.';

    /**
     * @var int
     */
    public int $max;

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return MultimediaNameValidator::class;
    }
}
