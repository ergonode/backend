<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MultimediaExtension extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'Multimedia extension {{ value }} not supported.';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return MultimediaExtensionValidator::class;
    }
}
