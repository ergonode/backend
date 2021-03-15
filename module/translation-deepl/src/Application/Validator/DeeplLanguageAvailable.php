<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\TranslationDeepl\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DeeplLanguageAvailable extends Constraint
{
    public string $message = '"{{ language }}" language is not supported';
}
