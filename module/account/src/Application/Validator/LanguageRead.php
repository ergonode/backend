<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LanguageRead extends Constraint
{
    public string $message = 'One read Language required for correct operation.';
}
