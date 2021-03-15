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
class LanguageCodeActive extends Constraint
{
    public string $message = 'Language code {{ value }} is not active';
}
