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
class MultimediaType extends Constraint
{
    public string $message = 'Multimedia is not an valid {{ type }} type.';

    public ?string $type = null;

    public function getDefaultOption(): string
    {
        return 'type';
    }

    public function getRequiredOptions(): array
    {
        return ['type'];
    }
}
