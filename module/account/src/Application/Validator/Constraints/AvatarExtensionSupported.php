<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AvatarExtensionSupported extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'Avatar extension {{ value }} not supported.';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return AvatarExtensionSupportedValidator::class;
    }
}
